<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;

Route::group(['middleware' => ['guest.api']], function () {
    Route::middleware(['throttle:login'])->post('/login',[AuthController::class, 'login']);
});

