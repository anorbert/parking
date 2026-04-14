<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Parking;
use App\Models\Slot;
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
use App\Services\ParkingService;

class ParkingController extends Controller
{
    protected ParkingService $parkingService;

    public function __construct(ParkingService $parkingService)
    {
        $this->parkingService = $parkingService;
    }

    public function index()
    {
        $parkings = Parking::where('zone_id', Auth::user()->zone_id)
                    ->where('company_id', Auth::user()->company_id)
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
        $request->validate([
            'plate_number' => 'required|string|max:255',
        ]);

        try {
            $this->parkingService->entry(
                $request->plate_number,
                Auth::user()->zone_id,
                Auth::user()->company_id
            );
            return back()->with('success', 'Car entered successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
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
    }

    public function exit(Request $request, $id)
    {
        $request->validate([
            'payment_method' => 'in:cash,momo',
            'phone_number' => 'nullable|min:10',
            'amount' => 'required|numeric',
        ]);

        try {
            $result = $this->parkingService->exit(
                $id,
                $request->payment_method,
                $request->phone_number,
                $request->amount
            );
            return response()->json($result);
        } catch (\Exception $e) {
            Log::error('Parking exit failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }

    public function exitInfo($id)
    {
        try {
            $result = $this->parkingService->getExitInfo($id);
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
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
            if ($parking && $parking->status !== 'inactive') {
                $parking->status = 'inactive';
                $parking->phone_number = $transaction->phone_number;
                $parking->payment_method = 'momo';
                $parking->save();

                // Free the slot
                if ($parking->slot_id) {
                    Slot::where('id', $parking->slot_id)->update(['is_occupied' => false]);
                }
            }
        }

        return response()->json(['status' => $transaction->status]);
    }

}