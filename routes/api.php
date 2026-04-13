<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Users\PaymentController;
use App\Http\Controllers\Users\ParkingController;
use App\Http\Controllers\SubscriptionPaymentController;

Route::post('/payment/callback', [PaymentController::class, 'handleCallback']);
Route::post('/momo/push/callback', [PaymentController::class, 'pushCallback']);

Route::get('/check-payment-status', [ParkingController::class, 'checkStatus']);

// Subscription MoMo payment
Route::post('/subscription/callback', [SubscriptionPaymentController::class, 'handleCallback']);
Route::get('/subscription/check-status', [SubscriptionPaymentController::class, 'checkStatus']);