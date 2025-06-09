<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Parking;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\ParkingRate;
use App\Models\Bank;
use App\Models\PaymentToken;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use App\Models\PaymentHistory;
use App\Models\Vehicle;

class ParkingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $parkings = Parking::where('zone_id', Auth::user()->zone_id) // Assuming user has zone_id
                    ->latest()
                    ->get();
        return view('users.parkings.index', compact('parkings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'plate_number' => 'required|string|max:255',
        ]);

        // Check if vehicle is already parked (no exit time)
        $existing = Parking::where('plate_number', $request->plate_number)
            ->whereNull('exit_time')
            ->first();

        if ($existing) {
            return back()->with('error', 'This vehicle is already parked.');
        }
        // return Auth::user()->zone_id;
        Parking::create([
            'plate_number' => $request->plate_number,
            'entry_time' => Carbon::now(),
            'zone_id' => Auth::user()->zone_id, // Assuming the zone is associated with the user
            // Optionally add 'zone' if provided
            'user_id' => Auth::id(), // Assuming you want to associate the parking with the logged-in user
        ]);

        return back()->with('success', 'Car entered successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $parking = Parking::findOrFail($id);
        $parking->exit_time = Carbon::now();
        $parking->save();
        return back()->with('success', 'Car exited successfully.');
    }

    
    public function exit(Request $request, $id)
    {
        $request->validate([
            'payment_method' => 'in:cash,momo',
            'phone_number' => 'nullable|min:10',
            'amount' => 'required|numeric',
        ]);

        $parking = Parking::where('id', $id)->whereNull('exit_time')->firstOrFail();
        $exitTime = now();
        $entryTime = Carbon::parse($parking->entry_time);
        $duration = $entryTime->diffInMinutes($exitTime);
        $amount = $request->amount;
        $paymentMethod = $request->payment_method;
        $phone = $request->phone_number;

        if ($paymentMethod === 'momo') {
            $bank = Bank::where('payment_owner', 'FDI')->firstOrFail();
            $token = PaymentToken::where('bank_id', $bank->id)->where('expired_at', '>', now())->first();

            if (!$token) {
                $res = Http::post('https://payments-api.fdibiz.com/v2/auth', [
                    'appId' => $bank->appId,
                    'secret' => $bank->secret,
                ]);

                if ($res->successful()) {
                    $data = $res->json();
                    $token = PaymentToken::create([
                        'bank_id' => $bank->id,
                        'token' => $data['data']['token'],
                        'expired_at' => $data['data']['expires_at'],
                    ]);
                } else {
                    return response()->json(['message' => 'Token retrieval failed'], 500);
                }
            }

            $number = preg_replace('/\D/', '', $phone);
            $prefix = substr($number, -9, 2);
            $trx_ref = Str::random(32);
            $operator = match ($prefix) {
                '78', '79' => 'momo-mtn-rw',
                '72', '73' => 'momo-airtel-rw',
                default => null,
            };

            if (!$operator) {
                return response()->json(['message' => 'Invalid phone number for payment.'], 422);
            }

            $res = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token->token,
                'Content-Type' => 'application/json',
            ])->post('https://payments-api.fdibiz.com/v2/momo/pull', [
                'trxRef' => $trx_ref,
                'channelId' => $operator,
                'accountId' => $bank->appId,
                'msisdn' => '250' . substr($number, -9),
                'amount' => $amount,
                'callback' => 'http://94.72.112.148:8020/api/payment/callback',
            ]);

            PaymentHistory::create([
                'parking_id' => $parking->id,
                'amount' => $amount,
                'phone_number' => $phone,
                'bank_id' => $bank->id,
                'type' => 'MOMO',
                'status' => 'Processing',
                'channel' => $operator,
                'description' => $res->json()['message'] ?? 'Payment initiated',
                'payment_method' => $paymentMethod,
                'trx_ref' => $trx_ref,
            ]);

            // Set the Parking to payment
            $parking->update([
                'exit_time' => $exitTime,
                'bill' => $amount,
                'total_time' => $duration,
                'phone_number' => $phone ?? null,
                'payment_method' => $paymentMethod,
                'user_id' => Auth::id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Payment initiated.', 
                'trx_ref' => $trx_ref]);
        }

        // Cash payment
        $parking->update([
            'exit_time' => $exitTime,
            'bill' => $amount,
            'total_time' => $duration,
            'status' => 'inactive',
            'phone_number' => $phone ?? null,
            'payment_method' => $paymentMethod,
            'user_id' => Auth::id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Payment complete.']);
    }

    public function exitInfo($id)
    {
        $parking = Parking::with('zone')->find($id); // assuming relation is zoneRelation

        if (!$parking || $parking->exit_time) {
            return response()->json(['success' => false, 'message' => 'Parking not found or already exited.']);
        }

        $entryTime = Carbon::parse($parking->entry_time);
        $exitTime = now();
        $duration = $entryTime->diffInMinutes($exitTime);

        $exemptedVehicle = Vehicle::where('plate_number', $parking->plate_number)
            ->first();

        $isExempted = false;

        if ($exemptedVehicle) {
            // Check if exempted and still valid
            if (
                $exemptedVehicle->billing_type != 'free' &&
                Carbon::parse($exemptedVehicle->expired_at)->isFuture()
            ) {
                $isExempted = true;
            }
        }

        // Determine payment amount
        if ($isExempted) {
            $amount = 0;
        } else {
            $rate = ParkingRate::where('zone_id', $parking->zone_id)
                ->where('duration_minutes', '<=', $duration)
                ->orderByDesc('duration_minutes')
                ->first();

            $amount = $rate ? $rate->rate : 0;
        }

        return response()->json([
            'success' => true,
            'plate_number' => $parking->plate_number,
            'zone_name' => optional($parking->zone)->name,
            'entry_time' => $entryTime->toDateTimeString(),
            'exit_time' => $exitTime->toDateTimeString(),
            'duration' => floor($duration / 60) . ' hr ' . ($duration % 60),
            'amount' => $amount,
        ]);
    }

    public function checkStatus(Request $request)
    {
        $request->validate([
            'trx_ref' => 'required|string',
        ]);

        $transaction = PaymentHistory::where('trx_ref', $request->trx_ref)->first();

        if (!$transaction) {
            return response()->json(['status' => 'NotFound']);
        }
        if ($transaction->status === 'Completed') {
            //Update the parking status to inactive
            $parking = Parking::where('id', $transaction->parking_id)->first();
            if ($parking) {
                $parking->status = 'inactive';
                $parking->phone_number = $transaction->phone_number;
                $parking->payment_method = 'momo';
                $parking->save();
            }
        }

        return response()->json(['status' => $transaction->status]);
    }

}