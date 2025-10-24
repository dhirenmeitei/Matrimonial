<?php

use App\Http\Controllers\DynamicFormController;
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

// Route::get('/register/{form_id}', [DynamicFormController::class, 'show']);
Route::get('/register', [DynamicFormController::class, 'show']);
Route::post('/register/{form_id}', [DynamicFormController::class, 'submit'])->name('form.submit');
