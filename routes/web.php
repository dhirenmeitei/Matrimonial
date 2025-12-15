<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RegistrationFormController;
use App\Http\Controllers\ProfileController;
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

// registration......................................................
// Route::get('/register/{form_id}', [RegistrationFormController::class, 'show']);
Route::get('/register', [RegistrationFormController::class, 'show']);
Route::post('/register/{form_id}', [RegistrationFormController::class, 'submit'])->name('form.submit');
// login................................................................
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Route::middleware('auth')->group(function () {
Route::middleware(['auth', 'nocache'])->group(function () {

    // routes/web.php
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
});
