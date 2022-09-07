<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DepartmentsController;
use Illuminate\Support\Facades\Route;

Route::get('departments', [DepartmentsController::class, 'index']); // get all departments with sections for each department
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    // employee requests routes
    Route::prefix('employee-requests')->group(function () {
        Route::get('/', [\App\Http\Controllers\Api\EmployeeRequestsController::class, 'index']);
        Route::get('/get-for-manager', [\App\Http\Controllers\Api\EmployeeRequestsController::class, 'listRequestsForManager']);
        Route::post('/', [\App\Http\Controllers\Api\EmployeeRequestsController::class, 'store']);
        Route::post('/change-status', [\App\Http\Controllers\Api\EmployeeRequestsController::class, 'changeRequestStatus']);
    });

    // notifications
    Route::get('notifications', [\App\Http\Controllers\Api\NotificationsController::class, 'index']);


    // Users
    Route::get('list-users-by-department', [\App\Http\Controllers\Api\UsersController::class, 'listByDepartment']);
});
