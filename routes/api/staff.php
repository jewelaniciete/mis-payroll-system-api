<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\StaffController;
use App\Http\Controllers\API\ExerciseTransactionController;

Route::prefix('staff')->middleware(['auth:api-staff','scopes:staff_user'])->group(function(){
    Route::get('/auth',[AuthController::class, 'auth']);
    Route::post('/logout',[AuthController::class, 'logout']);

    Route::get('/show-client', [StaffController::class, 'show_clients']);
    Route::post('/store-client', [StaffController::class, 'store_clients']);
    Route::get('/edit-client/{id}', [StaffController::class, 'edit_clients']);
    Route::post('/update-client/{id}', [StaffController::class, 'update_clients']);

    Route::post('/cart/add', [StaffController::class, 'add_to_cart']);
    Route::post('/cart/remove', [StaffController::class, 'remove_item']);
    Route::post('/cart/checkout', [StaffController::class, 'checkout']);

    Route::post('/exercise-transaction/add', [ExerciseTransactionController::class, 'store']);
    Route::get('/exercise-transaction/show', [ExerciseTransactionController::class, 'show']);
    Route::get('/exercise-transaction/archive', [ExerciseTransactionController::class, 'trashed_record_exercise_transaction']);
    Route::post('/exercise-transaction/delete/{transaction_code}', [ExerciseTransactionController::class, 'soft_delete_exercise_transaction']);
    Route::post('/exercise-transaction/restore/{transaction_code}', [ExerciseTransactionController::class, 'restore_record_exercise_transaction']);
    Route::post('/exercise-transaction/delete-permanent/{transaction_code}', [ExerciseTransactionController::class, 'force_delete_record_exercise_transaction']);

    Route::post('/add-security-answer', [StaffController::class, 'add_security_answer']);
});
