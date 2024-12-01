<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\AdminController;

Route::prefix('admin')->middleware(['auth:api-admin','scopes:admin_user'])->group(function(){
    Route::get('/auth',[AuthController::class, 'auth']);
    Route::post('/logout',[AuthController::class, 'logout']);

    Route::get('/show-client', [AdminController::class, 'show_clients']);
    Route::post('/store-client', [AdminController::class, 'store_clients']);
    Route::get('/edit-client/{id}', [AdminController::class, 'edit_clients']);
    Route::post('/update-client/{id}', [AdminController::class, 'update_clients']);

    Route::post('/soft-delete-client/{id}', [AdminController::class, 'soft_delete_clients']);       // soft delete
    Route::get('/archive-client', [AdminController::class, 'trashed_record_clients']);              // used to display archived records
    Route::post('/force-delete-client/{id}', [AdminController::class, 'force_delete_clients']);     // used to permanently delete records in archive
    Route::post('/restore-client/{id}', [AdminController::class, 'restore_clients']);               // used to restore deleted records in archive

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

    Route::get('show-attendance-list', [AdminController::class, 'show_staff_attendances']);
    Route::post('/store-attendance/{id}', [AdminController::class, 'store_staff_attendances']);
    Route::get('/edit-attendance/{id}', [AdminController::class, 'edit_staff_attendances']);
    Route::post('/update-attendance/{id}', [AdminController::class, 'update_staff_attendances']);

    Route::get('/show-staff-payroll', [AdminController::class, 'show_staff_payrolls']);
    Route::post('/store-staff-payroll/{id}', [AdminController::class, 'store_staff_payrolls']);
});
