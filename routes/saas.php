<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Saas\SaasLoginController;
use App\Http\Controllers\Saas\SaasController;
use App\Http\Controllers\Saas\SaasGimnasioController;
use App\Http\Controllers\Saas\SaasSuscripcionController;

Route::prefix('saas')->name('saas.')->group(function () {

    Route::get('login', [SaasLoginController::class, 'showLogin'])->name('login');
    Route::post('login', [SaasLoginController::class, 'login'])->name('login.post');
    Route::post('logout', [SaasLoginController::class, 'logout'])->name('logout');

    Route::middleware('auth.saas')->group(function () {
        Route::get('dashboard', [SaasController::class, 'dashboard'])->name('dashboard');

        Route::get('gimnasios',                    [SaasGimnasioController::class, 'index'])->name('gimnasios.index');
        Route::get('gimnasios/eliminados',         [SaasGimnasioController::class, 'eliminados'])->name('gimnasios.eliminados');
        Route::get('gimnasios/crear',              [SaasGimnasioController::class, 'create'])->name('gimnasios.create');
        Route::post('gimnasios',                   [SaasGimnasioController::class, 'store'])->name('gimnasios.store');
        Route::get('gimnasios/{id}',               [SaasGimnasioController::class, 'show'])->name('gimnasios.show');
        Route::get('gimnasios/{id}/editar',        [SaasGimnasioController::class, 'edit'])->name('gimnasios.edit');
        Route::put('gimnasios/{id}',               [SaasGimnasioController::class, 'update'])->name('gimnasios.update');
        Route::post('gimnasios/{id}/suspender',    [SaasGimnasioController::class, 'suspender'])->name('gimnasios.suspender');
        Route::post('gimnasios/{id}/reactivar',    [SaasGimnasioController::class, 'reactivar'])->name('gimnasios.reactivar');
        Route::post('gimnasios/{id}/cancelar',     [SaasGimnasioController::class, 'cancelar'])->name('gimnasios.cancelar');
        Route::post('gimnasios/{id}/restaurar',    [SaasGimnasioController::class, 'restaurar'])->name('gimnasios.restaurar');
        Route::post('gimnasios/{id}/suscripcion',  [SaasGimnasioController::class, 'asignarSuscripcion'])->name('gimnasios.suscripcion');

        Route::get('suscripciones',                      [SaasSuscripcionController::class, 'index'])->name('suscripciones.index');
        Route::get('suscripciones/{id}',                 [SaasSuscripcionController::class, 'show'])->name('suscripciones.show');
        Route::post('suscripciones/{id}/renovar',        [SaasSuscripcionController::class, 'renovar'])->name('suscripciones.renovar');
        Route::post('suscripciones/{id}/suspender',      [SaasSuscripcionController::class, 'suspender'])->name('suscripciones.suspender');
        Route::post('suscripciones/{id}/cancelar',       [SaasSuscripcionController::class, 'cancelar'])->name('suscripciones.cancelar');
    });
});
