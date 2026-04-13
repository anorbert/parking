<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Authentication\LoginController;
use App\Http\Controllers\Authentication\StaffController;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;

// Admin
use App\Http\Controllers\Admin\RateController;
use App\Http\Controllers\Admin\ZoneController;
use App\Http\Controllers\Admin\VehicleController;
use App\Http\Controllers\Admin\ExitLogsController;
use App\Http\Controllers\Admin\AdminPaymentController;
use App\Http\Controllers\Admin\AdminReportController;

// Users
use App\Http\Controllers\Users\ParkingController;
use App\Http\Controllers\Users\ExemptedVehicleController;
use App\Http\Controllers\Users\PaymentController;
use App\Http\Controllers\Users\UserReportController;

// Super Admin
use App\Http\Controllers\SuperAdmin\CompanyController;
use App\Http\Controllers\SuperAdmin\SubscriptionController;
use App\Http\Controllers\SuperAdmin\SuperAdminDashboardController;
use App\Http\Controllers\SuperAdmin\SuperAdminReportController;

Route::get('/', function () {
    return redirect()->route('login');
});

// Login routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'store'])->name('login.store');

// ─── Super Admin Routes ─────────────────────────────────────────────
Route::middleware(['auth', 'role:1'])->prefix('superadmin')->name('superadmin.')->group(function () {
    Route::get('/dashboard', [SuperAdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('companies', CompanyController::class);
    Route::get('/subscriptions', [SubscriptionController::class, 'index'])->name('subscriptions.index');
    Route::post('/subscriptions/{id}/activate', [SubscriptionController::class, 'activate'])->name('subscriptions.activate');
    Route::post('/subscriptions/{companyId}/renew', [SubscriptionController::class, 'renew'])->name('subscriptions.renew');
    Route::get('/reports', [SuperAdminReportController::class, 'index'])->name('reports.index');
});

// ─── Company Admin Routes ───────────────────────────────────────────
Route::middleware(['auth', 'role:2', 'company.scope', 'subscription'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::resource('staff', StaffController::class);
    Route::resource('vehicles', VehicleController::class);
    Route::resource('zones', ZoneController::class);
    Route::resource('rates', RateController::class);
    Route::resource('logs', ExitLogsController::class);
    Route::resource('payments', AdminPaymentController::class);
    Route::resource('reports', AdminReportController::class);
    Route::post('/zones/slot/store', [ZoneController::class, 'slotstore'])->name('zones.slotstore');
});

// ─── Cashier / Attendant Routes ─────────────────────────────────────
Route::middleware(['auth', 'role:3,4', 'company.scope', 'subscription'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [UserController::class, 'index'])->name('dashboard');
    Route::resource('parkings', ParkingController::class);
    Route::resource('vehicles', ExemptedVehicleController::class);
    Route::resource('payments', PaymentController::class);
    Route::resource('reports', UserReportController::class);

    Route::post('/parking/entry', [ParkingController::class, 'store'])->name('parking.entry');
    Route::post('/parking/exit/{id}', [ParkingController::class, 'exit'])->name('parking.exit');
    Route::get('/parking/exit-info/{id}', [ParkingController::class, 'exitInfo']);
});

// ─── Change PIN (all roles) ─────────────────────────────────────────
Route::middleware(['auth'])->prefix('user')->name('user.')->group(function () {
    Route::resource('change-pin', LoginController::class);
});

// ─── Subscription Expired ───────────────────────────────────────────
Route::middleware(['auth'])->group(function () {
    Route::get('/subscription/expired', function () {
        return view('subscription.expired');
    })->name('subscription.expired');
});

// Logout
Route::post('/logout', [LoginController::class, 'destroy'])->name('logout')->middleware('auth');

require __DIR__.'/auth.php';
