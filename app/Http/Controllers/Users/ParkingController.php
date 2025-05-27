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

class ParkingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
            'payment_method' => 'required|in:cash,momo',
            'phone_number' => 'required_if:payment_method,momo|min:10',
            'amount' => 'required|numeric',
        ]);

        if (!Auth::check()) {
            return back()->with('error', 'You must be logged in to exit a parking.');
        }

        Log::info("Exiting parking for ID: $id by user: " . Auth::id());

        $parking = Parking::where('id', $id)
            ->whereNull('exit_time')
            ->latest()
            ->first();

        if (!$parking) {
            return back()->with('error', 'Active parking not found.');
        }

        $exitTime = now();
        $entryTime = Carbon::parse($parking->entry_time);
        $duration = $entryTime->diffInMinutes($exitTime);
        $amount = $request->amount;
        $paymentMethod = $request->payment_method;
        $phone = $request->phone_number;

        if ($paymentMethod === 'momo') {
            $bank = Bank::where('payment_owner', 'FDI')->first();

            if (!$bank) {
                Log::error('Payment bank not configured for FDI.');
                throw new \Exception('Payment hooks not available.');
            }

            // Check or retrieve token
            $token = PaymentToken::where('bank_id', $bank->id)
                ->where('expired_at', '>', now())
                ->first();

            if (!$token) {
                $response = Http::post('https://payments-api.fdibiz.com/v2/auth', [
                    'appId' => $bank->appId,
                    'secret' => $bank->secret,
                ]);

                if ($response->successful()) {
                    $data = $response->json();

                    $token = PaymentToken::create([
                        'bank_id' => $bank->id,
                        'token' => $data['data']['token'],
                        'expired_at' => $data['data']['expires_at'],
                    ]);
                } else {
                    Log::error('Failed to retrieve token: ' . $response->body());
                    throw new \Exception('Failed to retrieve token.');
                }
            }

            // Detect operator
            $number = preg_replace('/\D/', '', $phone);
            $prefix = substr($number, -9, 2);
            $trx_ref=Str::random(32);
            $operator = match ($prefix) {
                '78', '79' => 'momo-mtn-rw',
                '72', '73' => 'momo-airtel-rw',
                default => throw new \Exception('Invalid phone number for payment.'),
            };

            // Send MoMo payment request
            $paymentResponse = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token->token,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->post('https://payments-api.fdibiz.com/v2/momo/pull', [
                'trxRef' => $trx_ref,
                'channelId' => $operator,
                'accountId' => $bank->appId,
                'msisdn' => '250' . substr($number, -9),
                'amount' => $amount,
                'callback' => 'http://94.72.112.148:8030/api/donation/callback',
            ]);

            $responseData = $paymentResponse->json();
            Log::info('Payment response: ' . json_encode($responseData));
            

            if (isset($responseData['status']) && $responseData['status'] === 'fail') {
                Log::error('Payment request failed: ' . ($responseData['message'] ?? 'Unknown error'));
                throw new \Exception('Payment request failed: ' . ($responseData['message'] ?? 'Unknown error'));
            }

            // Save the payment response for logging or further processing
            $paymentHistory = PaymentHistory::create([
                'parking_id' => $parking->id,
                'amount' => $amount,
                'phone_number' => $phone,
                'bank_id' => $bank->id,
                'type' => 'MOMO',
                'status' => 'Processing',
                'channel' => $operator,
                'description' => $responseData['message'] ?? 'Payment initiated',
                'payment_method' => $paymentMethod,
                'trx_ref' => $trx_ref,
            ]);

        }

        // If payment method is cash or MoMo, we proceed to update the parking record

        // Final update
        $parking->update([
            'exit_time' => $exitTime,
            'bill' => $amount,
            'total_time' => $duration,
            'status' => 'inactive',
            'phone_number' => $phone,
            'payment_method' => $paymentMethod,
            'user_id' => Auth::id(),
        ]);

        return back()->with('success', "Car exited. Duration: $duration min. Billed: RWF " . number_format($amount, 2));
    }


    
    // public function exit(Request $request, $id)
    // {
    //     $request->validate([
    //         'payment_method' => 'required|in:cash,momo',
    //         'phone_number' => 'required_if:payment_method,momo|min:10',
    //         'amount'  => 'required|numeric',
    //     ]);
    //     // Check if the parking record exists and is active
    //     if (!Auth::check()) {
    //         return back()->with('error', 'You must be logged in to exit a parking.');
    //     }
    //     if (!$id) {
    //         return back()->with('error', 'Parking ID is required.');
    //     }
    //     // Find the parking record by ID and check if it is active
    //     Log::info("Exiting parking for ID: $id by user: " . Auth::id());

    //     $parking = Parking::where('id', $id)
    //                     ->whereNull('exit_time')
    //                     ->latest()
    //                     ->first();

    //     if (!$parking) {
    //         return back()->with('error', 'Active parking not found.');
    //     }

    //     $entryTime = Carbon::parse($parking->entry_time);
    //     $exitTime = now();
    //     $duration = $entryTime->diffInMinutes($exitTime);

    //     $zoneId = $parking->zone;
    //     $amount = $request->amount;

    //     if ($request->payment_method === 'momo') {
    //          // Retrieve Payment Hook
    //         $bank = Bank::where('payment_owner', 'FDI')->first();
    //         if (!$bank) {
    //             Log::error('Payment bank not configured for FDI.');
    //             throw new \Exception('Payment hooks not available.');
    //         }

    //         // Check valid token
    //         $token = PaymentToken::where('bank_id', $bank->id)
    //             ->where('expired_at', '>', now())
    //             ->first();

    //         if (!$token) {
    //             $response = Http::post('https://payments-api.fdibiz.com/v2/auth', [
    //                 'appId' => $bank->appId,
    //                 'secret' => $bank->secret,
    //             ]);

    //             if ($response->status() == 200) {
    //                 $data = $response->json();

    //                 $token = PaymentToken::create([
    //                     'bank_id' => $bank->id,
    //                     'token' => $data['data']['token'],
    //                     'expired_at' => $data['data']['expires_at'], // Ensure this is in a datetime format
    //                 ]);
    //             } else {
    //                 Log::error('Failed to retrieve token: ' . $response->body());
    //                 throw new \Exception('Failed to retrieve token.');

    //             }
    //         }

    //         // Build payment info
    //         $trx_ref = Str::random(32);
    //         $amount = $request->amount;
    //         $phone = $request->phone_number;

    //         // Channel detection
    //         $number = preg_replace('/\D/', '', $phone); // keep only digits
    //         $prefix = substr($number, -9, 2);
    //         switch ($prefix) {
    //             case '78':
    //             case '79':
    //                 $operator = 'momo-mtn-rw';
    //                 break;
    //             case '72':
    //             case '73':
    //                 $operator = 'momo-airtel-rw';
    //                 break;
    //             default:
    //                 Log::error('Invalid phone number for payment: ' . $phone);
    //                 throw new \Exception('Invalid phone number for payment.');
    //         }

    //         // Send payment request
    //         $paymentResponse = Http::withHeaders([
    //             'Authorization' => 'Bearer ' . $token->token,
    //             'Content-Type' => 'application/json',
    //             'Accept' => 'application/json',
    //         ])->post('https://payments-api.fdibiz.com/v2/momo/pull', [
    //             'trxRef' => $trx_ref,
    //             'channelId' => $operator,
    //             'accountId' => $bank->appId,
    //             'msisdn' => '250' . substr($phone, -9),
    //             'amount' => $amount,
    //             'callback' => 'http://94.72.112.148:8030/api/donation/callback',
    //         ]);

    //         $responseData = $paymentResponse->json();
    //         Log::info('Payment response: ' . json_encode($responseData));


    //         if (isset($responseData['status']) && $responseData['status'] === 'fail') {
    //             Log::error('Payment request failed: ' . $responseData['message'] ?? 'Unknown error');
    //             throw new \Exception('Payment request failed: ' . $responseData['message'] ?? 'Unknown error');
    //         } 

    //         // Update the record
    //         $parking->update([
    //             'exit_time' => $exitTime,
    //             'bill' => $amount,
    //             'user_id' => Auth::id(),
    //         ]);
    //     }

    //     // Update the record
    //     $parking->update([
    //         'exit_time' => $exitTime,
    //         'bill' => $amount,
    //         'status' => 'inactive',
    //         'phone_number' => $request->phone_number,
    //         'payment_method' => $request->payment_method,
    //         'user_id' => Auth::id(),
    //     ]);
    //     return back()->with('success', "Car exited. Billed amount: " . number_format($amount, 2));
    // }

    public function exitInfo($id)
    {
        $parking = Parking::with('zone')->find($id); // assuming relation is zoneRelation

        if (!$parking || $parking->exit_time) {
            return response()->json(['success' => false, 'message' => 'Parking not found or already exited.']);
        }

        $entryTime = Carbon::parse($parking->entry_time);
        $exitTime = now();
        $duration = $entryTime->diffInMinutes($exitTime);;

        $rate = ParkingRate::where('zone_id', $parking->zone_id)
                    ->where('duration_minutes', '<=', $duration)
                    ->orderByDesc('duration_minutes')
                    ->first();

        $amount = $rate ? $rate->rate : 0;

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



}