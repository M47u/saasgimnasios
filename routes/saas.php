<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Saas\SaasLoginController;
use App\Http\Controllers\Saas\SaasController;
use App\Http\Controllers\Saas\SaasGimnasioController;
use App\Http\Controllers\Saas\SaasSuscripcionController;
use App\Http\Controllers\Saas\SaasPlanController;
use App\Http\Controllers\Saas\SaasEmpresaController;

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

        Route::get('planes',                  [SaasPlanController::class, 'index'])->name('planes.index');
        Route::get('planes/crear',            [SaasPlanController::class, 'create'])->name('planes.create');
        Route::post('planes',                 [SaasPlanController::class, 'store'])->name('planes.store');
        Route::get('planes/{id}/editar',      [SaasPlanController::class, 'edit'])->name('planes.edit');
        Route::put('planes/{id}',             [SaasPlanController::class, 'update'])->name('planes.update');
        Route::post('planes/{id}/toggle',     [SaasPlanController::class, 'toggleActivo'])->name('planes.toggle');
        Route::delete('planes/{id}',          [SaasPlanController::class, 'destroy'])->name('planes.destroy');

        Route::get('empresas',               [SaasEmpresaController::class, 'index'])->name('empresas.index');
        Route::get('empresas/{id}',          [SaasEmpresaController::class, 'show'])->name('empresas.show');
        Route::get('empresas/{id}/editar',   [SaasEmpresaController::class, 'edit'])->name('empresas.edit');
        Route::put('empresas/{id}',          [SaasEmpresaController::class, 'update'])->name('empresas.update');
        Route::delete('empresas/{id}',       [SaasEmpresaController::class, 'destroy'])->name('empresas.destroy');
    });
});
