<?php

namespace App\Services;

use App\Models\Parking;
use App\Models\ParkingRate;
use App\Models\Slot;
use App\Models\Vehicle;
use App\Models\PaymentHistory;
use App\Models\Bank;
use App\Models\PaymentToken;
use App\Models\User;
use App\Notifications\ParkingEntryNotification;
use App\Notifications\PaymentReceivedNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ParkingService
{
    /**
     * Register a new parking entry with automatic slot assignment.
     */
    public function entry(string $plateNumber, int $zoneId, ?int $companyId): Parking
    {
        return DB::transaction(function () use ($plateNumber, $zoneId, $companyId) {
            // Check duplicate active parking
            $existing = Parking::where('plate_number', $plateNumber)
                ->whereNull('exit_time')
                ->where('status', 'active')
                ->first();

            if ($existing) {
                throw new \Exception('This vehicle is already parked.');
            }

            // Find and assign available slot
            $slot = Slot::where('zone_id', $zoneId)
                ->where('is_occupied', false)
                ->lockForUpdate()
                ->first();

            if (!$slot) {
                throw new \Exception('No available slots in this zone.');
            }

            // Mark slot as occupied
            $slot->update(['is_occupied' => true]);

            // Create parking record
            $parking = Parking::create([
                'plate_number'   => $plateNumber,
                'entry_time'     => Carbon::now(),
                'zone_id'        => $zoneId,
                'slot_id'        => $slot->id,
                'user_id'        => Auth::id(),
                'company_id'     => $companyId,
                'status'         => 'active',
            ]);

            // Notify company admin
            $zoneName = $slot->zone->name ?? 'Zone';
            $admins = User::where('company_id', $companyId)->where('role_id', 2)->get();
            foreach ($admins as $admin) {
                $admin->notify(new ParkingEntryNotification($plateNumber, $zoneName));
            }

            return $parking;
        });
    }

    /**
     * Process parking exit: calculate bill, handle payment, free slot.
     */
    public function exit(int $parkingId, string $paymentMethod, ?string $phoneNumber, float $amount): array
    {
        return DB::transaction(function () use ($parkingId, $paymentMethod, $phoneNumber, $amount) {
            $parking = Parking::where('id', $parkingId)
                ->whereNull('exit_time')
                ->lockForUpdate()
                ->firstOrFail();

            $exitTime = now();
            $entryTime = Carbon::parse($parking->entry_time);
            $duration = $entryTime->diffInMinutes($exitTime);

            if ($paymentMethod === 'momo' && $phoneNumber) {
                $result = $this->processMomoPayment($parking, $amount, $phoneNumber, $exitTime, $duration);
                return $result;
            }

            // Cash payment
            $parking->update([
                'exit_time'       => $exitTime,
                'bill'            => $amount,
                'status'          => 'inactive',
                'phone_number'    => $phoneNumber,
                'payment_method'  => 'cash',
                'user_id'         => Auth::id(),
            ]);

            // Create payment history
            $bank = Bank::first();
            if ($bank) {
                PaymentHistory::create([
                    'parking_id'   => $parking->id,
                    'amount'       => $amount,
                    'bank_id'      => $bank->id,
                    'type'         => 'CASH',
                    'status'       => 'Completed',
                    'description'  => 'Cash payment collected',
                    'company_id'   => $parking->company_id,
                ]);
            }

            // Free the slot
            if ($parking->slot_id) {
                Slot::where('id', $parking->slot_id)->update(['is_occupied' => false]);
            }

            // Notify company admin of payment
            $admins = User::where('company_id', $parking->company_id)->where('role_id', 2)->get();
            foreach ($admins as $admin) {
                $admin->notify(new PaymentReceivedNotification($parking->plate_number, (int) $amount, 'cash'));
            }

            return ['success' => true, 'message' => 'Payment complete.'];
        });
    }

    /**
     * Calculate the bill for a parking session.
     */
    public function calculateBill(Parking $parking): float
    {
        $entryTime = Carbon::parse($parking->entry_time);
        $duration = $entryTime->diffInMinutes(now());

        // Check if exempted
        $exempted = Vehicle::where('plate_number', $parking->plate_number)
            ->where(function ($q) {
                $q->where('billing_type', 'free')
                    ->orWhere(function ($q2) {
                        $q2->where('expired_at', '>=', now());
                    });
            })->first();

        if ($exempted) {
            return 0;
        }

        $rate = ParkingRate::where('zone_id', $parking->zone_id)
            ->where('duration_minutes', '<=', $duration)
            ->orderByDesc('duration_minutes')
            ->first();

        return $rate ? (float) $rate->rate : 0;
    }

    /**
     * Get exit info for a parking record.
     */
    public function getExitInfo(int $parkingId): array
    {
        $parking = Parking::with('zone')->findOrFail($parkingId);

        if ($parking->exit_time) {
            throw new \Exception('Parking already exited.');
        }

        $entryTime = Carbon::parse($parking->entry_time);
        $exitTime = now();
        $duration = $entryTime->diffInMinutes($exitTime);
        $amount = $this->calculateBill($parking);

        return [
            'success'      => true,
            'plate_number' => $parking->plate_number,
            'zone_name'    => optional($parking->zone)->name,
            'entry_time'   => $entryTime->toDateTimeString(),
            'exit_time'    => $exitTime->toDateTimeString(),
            'duration'     => floor($duration / 60) . ' hr ' . ($duration % 60) . ' min',
            'amount'       => $amount,
        ];
    }

    /**
     * Process MoMo payment via FDI gateway.
     */
    private function processMomoPayment(Parking $parking, float $amount, string $phone, $exitTime, int $duration): array
    {
        $bank = Bank::where('payment_owner', 'FDI')->firstOrFail();
        $token = PaymentToken::where('bank_id', $bank->id)
            ->where('expired_at', '>', now())
            ->first();

        if (!$token) {
            $res = Http::post('https://payments-api.fdibiz.com/v2/auth', [
                'appId'  => $bank->appId,
                'secret' => $bank->secret,
            ]);

            if (!$res->successful()) {
                throw new \Exception('Payment token retrieval failed.');
            }

            $data = $res->json();
            $token = PaymentToken::create([
                'bank_id'    => $bank->id,
                'token'      => $data['data']['token'],
                'expired_at' => $data['data']['expires_at'],
            ]);
        }

        $number = preg_replace('/\D/', '', $phone);
        $prefix = substr($number, -9, 2);
        $trx_ref = Str::random(32);

        $operator = match ($prefix) {
            '78', '79' => 'momo-mtn-rw',
            '72', '73' => 'momo-airtel-rw',
            default    => null,
        };

        if (!$operator) {
            throw new \Exception('Invalid phone number for MoMo payment.');
        }

        $res = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token->token,
            'Content-Type'  => 'application/json',
        ])->post('https://payments-api.fdibiz.com/v2/momo/pull', [
            'trxRef'    => $trx_ref,
            'channelId' => $operator,
            'accountId' => $bank->appId,
            'msisdn'    => '250' . substr($number, -9),
            'amount'    => $amount,
            'callback'  => config('app.url') . '/api/payment/callback',
        ]);

        PaymentHistory::create([
            'parking_id'   => $parking->id,
            'amount'       => $amount,
            'phone_number' => $phone,
            'bank_id'      => $bank->id,
            'type'         => 'MOMO',
            'status'       => 'Processing',
            'channel'      => $operator,
            'description'  => $res->json()['message'] ?? 'Payment initiated',
            'trx_ref'      => $trx_ref,
            'company_id'   => $parking->company_id,
        ]);

        $parking->update([
            'exit_time'      => $exitTime,
            'bill'           => $amount,
            'phone_number'   => $phone,
            'payment_method' => 'momo',
            'user_id'        => Auth::id(),
        ]);

        return [
            'success' => true,
            'message' => 'Payment initiated.',
            'trx_ref' => $trx_ref,
        ];
    }
}
