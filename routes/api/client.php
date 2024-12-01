<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ClientController;

Route::prefix('client')->middleware(['auth:api-client','scopes:client_user'])->group(function(){
    Route::get('/auth',[AuthController::class, 'auth']);
    Route::post('/logout',[AuthController::class, 'logout']);

    Route::post('/avail-exercise/add', [ClientController::class, 'add_to_cart']);
    Route::post('/avail-exercise/remove', [ClientController::class, 'remove_from_cart']);
    Route::post('/avail-exercise/checkout', [ClientController::class, 'exercise_checkout']);

    Route::post('/avail-supplement/add', [ClientController::class, 'inventory_add_to_cart']);
    Route::post('/avail-supplement/remove', [ClientController::class, 'inventory_remove_item']);
    Route::post('/avail-supplement/checkout', [ClientController::class, 'inventory_checkout']);

    Route::post('/add-security-answer', [ClientController::class, 'add_security_answer']);
});
