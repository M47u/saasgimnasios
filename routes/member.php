<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Member\MemberLoginController;
use App\Http\Controllers\Member\MemberController;

Route::prefix('member')->name('member.')->group(function () {

    Route::get('login', [MemberLoginController::class, 'showLogin'])->name('login');
    Route::post('login', [MemberLoginController::class, 'login'])->name('login.post');
    Route::post('logout', [MemberLoginController::class, 'logout'])->name('logout');

    Route::middleware('auth.member')->group(function () {
        Route::get('dashboard', [MemberController::class, 'dashboard'])->name('dashboard');
    });
});
