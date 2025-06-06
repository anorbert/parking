<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Authentication\LoginController;
use App\Http\Controllers\Authentication\RegisterController;
use App\Http\Controllers\Authentication\StaffController;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\EditorController;
use App\Http\Controllers\UserController;

//Admin
use App\Http\Controllers\Admin\RateController;
use App\Http\Controllers\Admin\ZoneController;
use App\Http\Controllers\Admin\VehicleController;
use App\Http\Controllers\Admin\ExitLogsController;
use App\Http\Controllers\Admin\AdminPaymentController;
use App\Http\Controllers\Admin\AdminReportController;


use App\Http\Controllers\Users\ParkingController;
use App\Http\Controllers\Users\ExemptedVehicleController;
use App\Http\Controllers\Users\PaymentController;
use App\Http\Controllers\Users\UserReportController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('user_login',LoginController::class);
Route::resource('user_register',RegisterController::class);
Route::get('/user_register', [RegisterController::class, 'index'])->name('user_register');

Route::middleware(['auth'])->prefix('user')->name('user.')->group(function () {
    Route::resource('vehicles', ExemptedVehicleController::class);
    Route::resource('payments', PaymentController::class);
    Route::resource('reports', UserReportController::class);
    Route::resource('change-pin', LoginController::class);
});

// Login routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

// Register routes
Route::get('/register', [RegisterController::class, 'index'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);


// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    //Dashboard
    Route::resource('staff',StaffController::class);
    Route::resource('vehicles',VehicleController::class);
   
    Route::resource('logs',ExitLogsController::class);
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/editor/dashboard', [EditorController::class, 'index'])->name('editor.dashboard');
    Route::get('/user/dashboard', [UserController::class, 'index'])->name('user.dashboard');


    Route::post('/parking/entry', [ParkingController::class, 'store'])->name('parking.entry');
    Route::post('/parking/exit/{id}', [ParkingController::class, 'exit'])->name('parking.exit');
    Route::get('/parking/exit-info/{id}', [ParkingController::class, 'exitInfo']);

    // Route::get('/payment/status/{trx_ref}', [ParkingController::class, 'checkStatus'])->name('check.payment.status');

  




    Route::resource('parkings',ParkingController::class);   


    Route::resource('zones',ZoneController::class);
    Route::resource('rates',RateController::class);
    Route::resource('slots',RateController::class);

    Route::post('/Zone/slot/store', [ZoneController::class, 'slotstore'])->name('zones.slotstore');
    

});

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // Route::resource('exempted-vehicles', ExemptedVehicleController::class);
    Route::resource('payments', AdminPaymentController::class);
    Route::resource('reports', AdminReportController::class);

});


require __DIR__.'/auth.php';
