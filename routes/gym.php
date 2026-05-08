<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Gym\GymLoginController;
use App\Http\Controllers\Gym\GymController;
use App\Http\Controllers\Gym\GymPasswordController;

Route::prefix('gym')->name('gym.')->group(function () {

    Route::get('login', [GymLoginController::class, 'showLogin'])->name('login');
    Route::post('login', [GymLoginController::class, 'login'])->name('login.post');
    Route::post('login-select-gym', [GymLoginController::class, 'loginSelectGym'])->name('login.select-gym');
    Route::post('logout', [GymLoginController::class, 'logout'])->name('logout');

    Route::middleware('auth.gym')->group(function () {
        // Cambio de contraseña — accesible aunque must_change_password sea true
        Route::get('change-password', [GymPasswordController::class, 'show'])->name('password.change');
        Route::put('change-password', [GymPasswordController::class, 'update'])->name('password.update');

        // Rutas bloqueadas hasta que el usuario haya cambiado su contraseña
        Route::middleware('gym.force-password-change')->group(function () {
            Route::get('dashboard', [GymController::class, 'dashboard'])->name('dashboard');
        });
    });
});
