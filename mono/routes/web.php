<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PythonController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('user', UserController::class);

Route::get('/finger-control', function () {
    return view('finger-count');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');