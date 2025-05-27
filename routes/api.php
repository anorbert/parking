<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Users\PaymentController;

Route::post('/payment/callback', [PaymentController::class, 'handleCallback']);
Route::post('/momo/push/callback', [PaymentController::class, 'pushCallback']);