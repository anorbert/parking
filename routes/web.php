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


use App\Http\Controllers\Users\ParkingController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('user_login',LoginController::class);

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    //Dashboard
    Route::resource('staff',StaffController::class);
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/editor/dashboard', [EditorController::class, 'index'])->name('editor.dashboard');
    Route::get('/user/dashboard', [UserController::class, 'index'])->name('user.dashboard');


    Route::post('/parking/entry', [ParkingController::class, 'store'])->name('parking.entry');
    Route::post('/parking/exit/{id}', [ParkingController::class, 'exit'])->name('parking.exit');
    Route::get('/parking/exit-info/{id}', [ParkingController::class, 'exitInfo']);


    Route::resource('zones',ZoneController::class);
    Route::resource('rates',RateController::class);
    Route::resource('slots',RateController::class);

    Route::post('/Zone/slot/store', [ZoneController::class, 'slotstore'])->name('zones.slotstore');
    

});

require __DIR__.'/auth.php';
