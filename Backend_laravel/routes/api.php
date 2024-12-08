<?php

use App\Http\Controllers\API\AttendanceController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ManagerController;
use App\Http\Controllers\API\MonthlySalaryController;
use App\Http\Controllers\API\OvertimeController;
use App\Http\Controllers\API\RequestController;
use App\Http\Controllers\API\SalaryController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\Api\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', action: function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth',
], function ($router) {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');
    Route::post('/refresh', [AuthController::class, 'refresh'])->middleware('auth:api');
    Route::post('/profile', [AuthController::class, 'profile'])->middleware('auth:api');
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'users',
], function ($router) {
    Route::get('/search', [UserController::class, 'search']);
    Route::post('/update-face-id/{id}', [UserController::class, 'updateFaceId']);
    Route::get('/count-users', [UserController::class, 'countUsers']);  
    Route::get('/', [UserController::class, 'index']);
    Route::post('/store', [UserController::class, 'store']);
    Route::get('/{id}', [UserController::class, 'show']);
    Route::post('/update/{id}', [UserController::class, 'update']);
    Route::delete('/delete/{id}', [UserController::class, 'destroy']);
    Route::put('/{id}/update-manager', [UserController::class, 'updateManager']);
    Route::get('/get-managerName/{id}', [UserController::class, 'getManagerName']);
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'managers',
], function ($router) {
    Route::get('/', [ManagerController::class, 'index']);
    Route::get('/{id}/subordinates', [ManagerController::class, 'getSubordinates']);
    Route::get('/managers-with-subordinates', [ManagerController::class, 'getManagersWithSubordinates']);
    Route::post('/{id}/add-subordinate', [ManagerController::class, 'addSubordinate']);
    Route::get('/get-manager/{id}', [ManagerController::class, 'getManager']);
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'requests',
], function ($router) {
    Route::get('/', [RequestController::class, 'index']);
    Route::post('/', [RequestController::class, 'store']);
    Route::get('/{id}', [RequestController::class, 'show']);
    Route::put('/approve/{id}', [RequestController::class, 'approve']);
    Route::put('/reject/{id}', [RequestController::class, 'reject']);
    Route::get('/user/{id}', [RequestController::class, 'getRequestsForUser']);
    Route::get('/manager/{id}', [RequestController::class, 'getRequestsForManager']);
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'overtimes',
], function ($router) {
    Route::get('/', [OvertimeController::class, 'index']);
    Route::post('/', [OvertimeController::class, 'store']);
    Route::get('/total-overtime-hours-in-month-for-all-users', [OvertimeController::class, 'totalOvertimeHoursInMonthForAllUsers']);
    Route::get('/monthly-hours', [OvertimeController::class, 'getMonthlyOvertimeHours']);
    Route::put('/approve/{id}', [OvertimeController::class, 'approve']);
    Route::put('/reject/{id}', [OvertimeController::class, 'reject']);
    Route::get('/user/{id}', [OvertimeController::class, 'overtimeList']);
    Route::get('/manager/{id}', [OvertimeController::class, 'getOvertimeRequestsForManager']);
    Route::get('/count-overtime-hours-in-month-for-all-users', [OvertimeController::class, 'getUserOvertimeRanking']);
    Route::get('/{id}', [OvertimeController::class, 'show']);
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'attendance',
], function ($router) {
    Route::get('/count-days-checkin-in-month-for-all-users', [AttendanceController::class, 'countDaysCheckinInMonthForAllUsers']);
    Route::get('/count-days-checkin-late-or-checkout-early-for-all-users', [AttendanceController::class, 'countDaysCheckinLateOrCheckoutEarlyInMonthForAllUsers']);
    Route::get('/average-timeoff-in-month-for-all-users', [AttendanceController::class, 'averageTimeOffInMonthForAllUsers']);
    Route::get('/{id}/get-attendance-by-user-id', [AttendanceController::class, 'getAttendanceByUserId']);
    Route::get('/{username}', [AttendanceController::class, 'index']);
    Route::post('/{username}', [AttendanceController::class, 'checkAttendance']);
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'salary',
], function ($router) {
    Route::get('/{id}/get-salary-by-user-id', [SalaryController::class, 'getSalaryByUserId']);
    Route::get('/', [SalaryController::class, 'index']);
    Route::post('/create-or-update-basic-salary', [SalaryController::class, 'creatOrUpdateBasicSalary']);
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'monthly-salary',
], function ($router) {
    Route::get('/get-monthly-salary-by-month-and-year', [MonthlySalaryController::class, 'getMonthlySalaryByMonthAndYear']);
    Route::get('/get-average-salary-by-month-and-year', [MonthlySalaryController::class, 'getAverageSalaryByMonthAndYear']);
    Route::get('/{id}/get-monthly-salary-by-user-id', [MonthlySalaryController::class, 'getMonthlySalaryByUserId']);
    Route::get('/', [MonthlySalaryController::class, 'index']);
    Route::post('/', [MonthlySalaryController::class, 'store']);
    Route::get('/{id}', [MonthlySalaryController::class, 'show']);
    Route::put('/{id}', [MonthlySalaryController::class, 'update']);

    //createMonthlySalaryForUsers
    Route::post('/create-monthly-salary-for-users', [MonthlySalaryController::class, 'createMonthlySalaryForUsers']);
    //getMonthlySalaryByMonthAndYear
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'tasks',
], function ($router) {
    Route::get('/', [TaskController::class, 'index']);
    Route::post('/', [TaskController::class, 'store']);
    Route::get('/get-tasks-completed-in-month', [TaskController::class, 'getTasksCompletedInMonth']);
    Route::get('/userOrAssignedTasks/{userId}', [TaskController::class, 'userOrAssignedTasks']);
    Route::get('/user/{userId}', [TaskController::class, 'userTasks']);
    Route::get('/assigned/{userId}', [TaskController::class, 'assignedTasks']);
    Route::put('/{id}/assigned-to', [TaskController::class, 'updateAssignedTo']);
    Route::get('/{id}', [TaskController::class, 'show']);
    Route::put('/{id}/status', [TaskController::class, 'updateStatus']);
    Route::put('/{id}', [TaskController::class, 'update']);
    Route::delete('/{id}', [TaskController::class, 'destroy']);
    Route::put('/{id}/due-date', [TaskController::class, 'updateDueDate']);
});