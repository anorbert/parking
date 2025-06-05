<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Users\PaymentController;
use App\Http\Controllers\Users\ParkingController;

Route::post('/payment/callback', [PaymentController::class, 'handleCallback']);
Route::post('/momo/push/callback', [PaymentController::class, 'pushCallback']);

Route::get('/check-payment-status', [ParkingController::class, 'checkStatus']);