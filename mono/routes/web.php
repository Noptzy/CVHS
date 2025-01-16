<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PythonController;



Route::get('/', function () {
    return view('homepage');
});

Route::get('/camera', function () {
    return view('camera');
});
Route::get('/dashboard', function () {
    return view('dashboard2');
})->middleware('auth')->name('dashboard');


Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.index');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register.index');
Route::post('/register', [AuthController::class, 'register'])->name('register');
