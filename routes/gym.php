<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Gym\GymLoginController;
use App\Http\Controllers\Gym\GymController;

Route::prefix('gym')->name('gym.')->group(function () {

    Route::get('login', [GymLoginController::class, 'showLogin'])->name('login');
    Route::post('login', [GymLoginController::class, 'login'])->name('login.post');
    Route::post('logout', [GymLoginController::class, 'logout'])->name('logout');

    Route::middleware('auth.gym')->group(function () {
        Route::get('dashboard', [GymController::class, 'dashboard'])->name('dashboard');
    });
});
