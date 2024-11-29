<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\StaffController;

Route::prefix('staff')->middleware(['auth:api-staff','scopes:staff_user'])->group(function(){
    Route::get('/auth',[AuthController::class, 'auth']);
    Route::post('/logout',[AuthController::class, 'logout']);

    Route::get('/show-client', [StaffController::class, 'show_clients']);
    Route::post('/store-client', [StaffController::class, 'store_clients']);
    Route::get('/edit-client/{id}', [StaffController::class, 'edit_clients']);
    Route::post('/update-client/{id}', [StaffController::class, 'update_clients']);

    Route::post('/add-to-cart', [StaffController::class, 'add_to_cart']);
    Route::post('/checkout', [StaffController::class, 'checkout']);
});
