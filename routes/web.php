<?php

use App\Http\Controllers\EmailConfirmationController;
use App\Http\Controllers\FileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('uploads/identificationFiles/{identificationFileName}', [FileController::class, 'returnIdentificationFiles']);

Route::get('uploads/userImages/{imageName}', [FileController::class, 'returnUserImage']);

Route::get('uploads/gymImages/{imageName}', [FileController::class, 'returnGymImage']);

Route::get('/email/verify/{user}', [EmailConfirmationController::class, 'verify'])->name('email.confirmation');
