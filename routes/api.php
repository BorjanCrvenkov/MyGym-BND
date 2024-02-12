<?php

use App\Http\Controllers\Auth\AuthenticationController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\EmailConfirmationController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\ExcelReportsController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ExpenseTypeController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\GymController;
use App\Http\Controllers\MembershipController;
use App\Http\Controllers\MembershipTypeController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WorkingScheduleController;
use App\Http\Controllers\WorkingTimeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('auth')->controller(AuthenticationController::class)->group(function () {
    Route::post('/login', 'login')->name('login');
    Route::post('/register', 'register')->name('register');

    Route::post('/password/forgot', [ForgotPasswordController::class, 'forgotPassword'])->name('password.forgot');
    Route::post('/password/reset', [ResetPasswordController::class, 'resetPassword'])->name('password.reset');


    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', 'logout')->name('logout');
        Route::get('/user', 'getAuthenticatedUser')->name('getAuthenticatedUser');
        Route::get('/refreshToken', 'refreshToken')->name('refreshToken');
    });
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/users/send_email_verification_notification', [EmailConfirmationController::class, 'sendEmailConfirmationNotification']);

    Route::apiResource('users', UserController::class)
        ->except('update');
    Route::post('users/{user}', [UserController::class, 'update'])
        ->name('update');

    Route::apiResource('roles', RoleController::class);

    Route::apiResource('gyms', GymController::class)
        ->except('update');
    Route::post('gyms/{gym}', [GymController::class, 'update'])
        ->name('update');

    Route::apiResource('membership_types', MembershipTypeController::class);

    Route::apiResource('equipments', EquipmentController::class)
        ->except('update');
    Route::post('equipments/{equipment}', [EquipmentController::class, 'update'])
        ->name('update');

    Route::get('files', [FileController::class, 'index']);
    Route::post('files', [FileController::class, 'store']);
    Route::delete('files/{file}', [FileController::class, 'destroy']);

    Route::apiResource('memberships', MembershipController::class)
        ->except('update');

    Route::apiResource('sessions', SessionController::class);

    Route::apiResource('reviews', ReviewController::class);

    Route::apiResource('reports', ReportController::class);

    Route::apiResource('plans', PlanController::class);
    Route::post('/plans/subscribe', [PlanController::class, 'subscribe'])->name('subscribe');

    Route::apiResource('expense_types', ExpenseTypeController::class);

    Route::apiResource('expenses', ExpenseController::class);

    Route::prefix('excel_reports')->group(function () {
        Route::get('/sessions', [ExcelReportsController::class, 'sessionsReport'])->name('sessions-report');
        Route::get('/memberships_bought', [ExcelReportsController::class, 'membershipTypesBoughtReport'])->name('membership-types-bought-report');
        Route::get('/finance', [ExcelReportsController::class, 'financeReport'])->name('finance-report');
        Route::get('/general', [ExcelReportsController::class, 'generalReport'])->name('general-report');
    });

    Route::apiResource('working_schedules', WorkingScheduleController::class)
        ->except('store', 'update', 'destroy');

    Route::apiResource('working_times', WorkingTimeController::class);
});

