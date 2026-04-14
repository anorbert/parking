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
use App\Http\Controllers\SuperAdmin\PlanController;
use App\Http\Controllers\SuperAdmin\SuperAdminDashboardController;
use App\Http\Controllers\SuperAdmin\SuperAdminReportController;
use App\Http\Controllers\SubscriptionPaymentController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Admin\SubscriptionController as AdminSubscriptionController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/about', function () {
    return view('about');
})->name('about');

// Login & Registration routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'store'])->name('login.store');
Route::get('/register', [\App\Http\Controllers\Authentication\RegisterController::class, 'index'])->name('register');
Route::post('/register', [\App\Http\Controllers\Authentication\RegisterController::class, 'store'])->name('register.store');

// ─── Super Admin Routes ─────────────────────────────────────────────
Route::middleware(['auth', 'role:1'])->prefix('superadmin')->name('superadmin.')->group(function () {
    Route::get('/dashboard', [SuperAdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('companies', CompanyController::class);
    Route::resource('plans', PlanController::class)->except(['show']);
    Route::get('/subscriptions', [SubscriptionController::class, 'index'])->name('subscriptions.index');
    Route::post('/subscriptions/{id}/activate', [SubscriptionController::class, 'activate'])->name('subscriptions.activate');
    Route::post('/subscriptions/{companyId}/renew', [SubscriptionController::class, 'renew'])->name('subscriptions.renew');
    Route::get('/reports', [SuperAdminReportController::class, 'index'])->name('reports.index');
});

// ─── Company Admin Routes ───────────────────────────────────────────
Route::middleware(['auth', 'role:2'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    // Company creation for company admin
    Route::get('companies/create', [\App\Http\Controllers\Admin\CompanyController::class, 'create'])->name('companies.create');
    Route::post('companies', [\App\Http\Controllers\Admin\CompanyController::class, 'store'])->name('companies.store');
    // Company profile (view & edit own company)
    Route::get('company', [\App\Http\Controllers\Admin\CompanyController::class, 'profile'])->name('company.profile');
    Route::put('company', [\App\Http\Controllers\Admin\CompanyController::class, 'updateCompany'])->name('company.update');

    // Subscription management (accessible even when expired)
    Route::get('subscription', [AdminSubscriptionController::class, 'index'])->name('subscription.index');
    Route::post('subscription/renew', [AdminSubscriptionController::class, 'renew'])->name('subscription.renew');
    Route::post('subscription/upgrade', [AdminSubscriptionController::class, 'upgrade'])->name('subscription.upgrade');
});
// ─── Company Admin Routes ───────────────────────────────────────────
Route::middleware(['auth', 'role:2', 'subscription'])->prefix('admin')->name('admin.')->group(function () {
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

// ─── Account (Profile & Settings — all roles) ───────────────────────
Route::middleware(['auth'])->prefix('account')->name('account.')->group(function () {
    Route::get('/profile', [AccountController::class, 'profile'])->name('profile');
    Route::put('/profile', [AccountController::class, 'updateProfile'])->name('profile.update');
    Route::get('/settings', [AccountController::class, 'settings'])->name('settings');
    Route::put('/settings/pin', [AccountController::class, 'updatePin'])->name('pin.update');
});

// ─── Notifications (all roles) ──────────────────────────────────────
Route::middleware(['auth'])->prefix('notifications')->name('notifications.')->group(function () {
    Route::get('/', [NotificationController::class, 'index'])->name('index');
    Route::post('/{id}/read', [NotificationController::class, 'markAsRead'])->name('read');
    Route::post('/read-all', [NotificationController::class, 'markAllRead'])->name('readAll');
});

// ─── Help & Support (all roles) ─────────────────────────────────────
Route::middleware(['auth'])->prefix('help')->name('help.')->group(function () {
    Route::get('/guide', [\App\Http\Controllers\HelpController::class, 'guide'])->name('guide');
    Route::get('/chat', [\App\Http\Controllers\HelpController::class, 'chat'])->name('chat');
    Route::post('/chat/send', [\App\Http\Controllers\HelpController::class, 'send'])->name('chat.send');
    Route::get('/chat/messages', [\App\Http\Controllers\HelpController::class, 'messages'])->name('chat.messages');
    Route::get('/chat/unread', [\App\Http\Controllers\HelpController::class, 'unreadCount'])->name('chat.unread');
    Route::get('/chat/contacts', [\App\Http\Controllers\HelpController::class, 'contacts'])->name('chat.contacts');
    Route::get('/chat/{userId}', [\App\Http\Controllers\HelpController::class, 'conversation'])->name('chat.conversation');
});

// ─── Subscription Expired ───────────────────────────────────────────
Route::middleware(['auth'])->group(function () {
    Route::get('/subscription/expired', [SubscriptionPaymentController::class, 'expired'])->name('subscription.expired');
    Route::post('/subscription/pay', [SubscriptionPaymentController::class, 'payWithMomo'])->name('subscription.pay');
    Route::get('/subscription/processing', [SubscriptionPaymentController::class, 'processing'])->name('subscription.processing');
});

// Logout
Route::post('/logout', [LoginController::class, 'destroy'])->name('logout')->middleware('auth');

require __DIR__.'/auth.php';
