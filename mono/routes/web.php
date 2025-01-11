<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PythonController;


Route::resource('user', UserController::class);

Route::get('/finger-control', function () {
    return view('finger-count');
});

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');