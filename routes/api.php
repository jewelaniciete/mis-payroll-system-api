<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\AdminController;
use App\Http\Controllers\API\StaffController;

Route::group(['middleware' => ['guest.api']], function () {
    Route::middleware(['throttle:login'])->post('/login',[AuthController::class, 'login']);
});

Route::prefix('admin')->group(function () {
    Route::get('/show-client', [AdminController::class, 'show_clients']);
    Route::post('/store-client', [AdminController::class, 'store_clients']);
    Route::get('/edit-client/{id}', [AdminController::class, 'edit_clients']);
    Route::post('/update-client/{id}', [AdminController::class, 'update_clients']);

    Route::get('/show-staff', [AdminController::class, 'show_staffs']);
    Route::post('/store-staff', [AdminController::class, 'store_staffs']);
    Route::get('/edit-staff/{id}', [AdminController::class, 'edit_staffs']);
    Route::post('/update-staff/{id}', [AdminController::class, 'update_staffs']);

    Route::get('show-exercise', [AdminController::class, 'show_exercises']);
    Route::post('/store-exercise', [AdminController::class, 'store_exercises']);
    Route::get('/edit-exercise/{id}', [AdminController::class, 'edit_exercises']);
    Route::post('/update-exercise/{id}', [AdminController::class, 'update_exercises']);

    Route::get('show-position', [AdminController::class, 'show_positions']);
    Route::post('/store-position', [AdminController::class, 'store_positions']);
    Route::get('/edit-position/{id}', [AdminController::class, 'edit_positions']);
    Route::post('/update-position/{id}', [AdminController::class, 'update_positions']);

    Route::get('show-inventory', [AdminController::class, 'show_inventories']);
    Route::post('/store-inventory', [AdminController::class, 'store_inventories']);
    Route::get('/edit-inventory/{id}', [AdminController::class, 'edit_inventories']);
    Route::post('/update-inventory/{id}', [AdminController::class, 'update_inventories']);

});

Route::prefix('staff')->group(function () {
    Route::get('/show-client', [StaffController::class, 'show_clients']);
    Route::post('/store-client', [StaffController::class, 'store_clients']);
    Route::get('/edit-client/{id}', [StaffController::class, 'edit_clients']);
    Route::post('/update-client/{id}', [StaffController::class, 'update_clients']);
});
