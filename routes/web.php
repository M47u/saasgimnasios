<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

require __DIR__.'/saas.php';
require __DIR__.'/gym.php';
require __DIR__.'/member.php';
