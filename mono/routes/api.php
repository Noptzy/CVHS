<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LEDController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/led/{room}', [LedController::class, 'getLedState']);
Route::post('/led/{room}', [LedController::class, 'setLedState']);
Route::get('/distance', [LedController::class, 'getDistance']);