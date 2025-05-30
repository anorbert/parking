<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Parking;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $query = Parking::with('user')->where('user_id', Auth::id());

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        $payments = $query->latest()->get();

        return view('users.payments.index', compact('payments'));
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
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
    }

    public function handleCallback(Request $request)
    {
        // Log::info('Received callback payload:', $request->all());

        try {
            // Validate the callback payload
            $request->validate([
                'status' => 'required|string',
                'data' => 'required|array',
                'data.state' => 'required|string',
                'data.gwRef' => 'required|string',
                'data.trxRef' => 'required|string',
            ]);

            $status = $request->input('status');
            $data = $request->input('data');
            $trxRef = $data['trxRef'];
            $gwRef = $data['gwRef'];
            $state = $data['state'];

            // Find the transaction using the trxRef
            $transaction = PaymentHistory::where('trx_ref', $trxRef)->first();

            if (!$transaction) {                
                // Log::error('Transaction not found:', $trxRef);
                return response()->json(['message' => 'Transaction not found.'], 404);
            }
            if( $transaction->status=='Completed'){
                // Log::error('Transaction already processed:', $trxRef);
                return response()->json(['message' => 'Transaction already processed.'], 200);
            }

            if ($status === 'success' && $state === 'successful') {
                // Log::info('Transaction successful:', $trxRef);
                $bank = $transaction->bank;
                if (!$bank) {
                    // Log::error('Bank not found for transaction:', $trxRef);
                    return response()->json(['message' => 'Bank not found.'], 404);
                }
                // Update the bank balance and charges
                $charges= $transaction->bank->charges;
                $balance= $transaction->bank->balance;
                $newBalance= $transaction->amount-($transaction->amount * $charges/100);                
                $bank->balance = $balance + $newBalance;
                // Save the updated bank balance                
                $bank->save();
                // Log::info('Bank balance updated:', $bank->balance);
                
                // Update the transaction status to Completed
                $transaction->update(['status' => 'Completed','gwRef'=>$gwRef]);      
                
                return response()->json(['message' => 'Transaction successfully processed.'], 200);
            }

            if ($status === 'fail' && $state === 'failed') {
                // Log the failure reason
                $failureMessage = $data['message'] ?? 'Unknown error occurred';
                $transaction->update([
                    'status' => 'Failed',
                    'description' => $failureMessage,
                ]);

                return response()->json(['message' => 'Transaction failed.', 'reason' => $failureMessage], 200);
            }

            return response()->json(['message' => 'Invalid callback payload.'], 400);
        } catch (\Exception $e) {
            // Correct way: Pass the message as the first argument, and pass an array as the second argument.
            // Log::error('Callback processing failed', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }


    public function pushCallback(Request $request)
    {
        // Log::info('Received callback payload:', $request->all());

        try {
            // Validate the callback payload
            $request->validate([
                'status' => 'required|string',
                'data' => 'required|array',
                'data.state' => 'required|string',
                'data.gwRef' => 'required|string',
                'data.trxRef' => 'required|string',
            ]);

            $status = $request->input('status');
            $data = $request->input('data');
            $trxRef = $data['trxRef'];
            $gwRef = $data['gwRef'];
            $state = $data['state'];

            // Find the transaction using the trxRef
            $transaction = PaymentHistory::where('trx_ref', $trxRef)->first();


            if (!$transaction) {                
                // Log::error('Transaction not found:', $trxRef);
                return response()->json(['message' => 'Transaction not found.'], 404);
            }
            if( $transaction->status=='Completed'){
                // Log::error('Transaction already processed:', $trxRef);
                return response()->json(['message' => 'Transaction already processed.'], 200);
            }

            if ($status === 'success' && $state === 'successful') {
                // Log::info('Transaction successful:', $trxRef);
                $bank = $transaction->bank;
                if (!$bank) {
                    // Log::error('Bank not found for transaction:', $trxRef);
                    return response()->json(['message' => 'Bank not found.'], 404);
                }
                // Update the bank balance and charges
                $charges= $transaction->bank->charges;
                $balance= $transaction->bank->balance;
                $newBalance= $transaction->amount-($transaction->amount * $charges/100);                
                $bank->balance = $balance - $newBalance;
                // Save the updated bank balance                
                $bank->save();
                // Log::info('Bank balance updated:', $bank->balance);

                
                // Update the transaction status to Completed
                $transaction->update(['status' => 'Completed','gwRef'=>$gwRef]);

            
                
                return response()->json(['message' => 'Transaction successfully processed.'], 200);
            }

            if ($status === 'fail' && $state === 'failed') {
                // Log the failure reason
                $failureMessage = $data['message'] ?? 'Unknown error occurred';
                $transaction->update([
                    'status' => 'Failed',
                    'description' => $failureMessage,
                ]);

                return response()->json(['message' => 'Transaction failed.', 'reason' => $failureMessage], 200);
            }

            return response()->json(['message' => 'Invalid callback payload.'], 400);
        } catch (\Exception $e) {
            // Correct way: Pass the message as the first argument, and pass an array as the second argument.
            // Log::error('Callback processing failed', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }
}
