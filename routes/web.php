<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\RegistrationFormController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TimelineController;
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
    return view('home');
});

// registration......................................................
// Route::get('/register/{form_id}', [RegistrationFormController::class, 'show']);
Route::get('/register', [RegistrationFormController::class, 'show'])->name('register');
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
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/profile/{id}', [ProfileController::class, 'view'])->name('profile.viewprofile');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    // Timeline...................................
    Route::get('/timeline', [TimelineController::class, 'index'])->name('timeline');
    Route::post('/timeline/store', [TimelineController::class, 'store'])->name('post.store');
    Route::post('/timeline/like/{id}', [TimelineController::class, 'like'])->name('post.like');
    Route::post('/timeline/comment/{id}', [TimelineController::class, 'comment'])->name('post.comment');

    // gallery
    Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery.index');

    // Post
    Route::get('/post/{post}', [PostController::class, 'show'])->name('post.show');
    Route::post('/post/store', [TimelineController::class, 'store'])->name('post.store');
    Route::post('/post/{id}/like', [TimelineController::class, 'like'])->name('post.like');
    Route::delete('/post/{post}', [PostController::class, 'destroy'])->name('post.destroy');
    Route::post('/post/{id}/comment', [TimelineController::class, 'comment'])->name('post.comment');


    // comment
    Route::delete('/comment/{comment}', [TimelineController::class, 'deleteComment'])
        ->name('comment.delete');


    // Find friends
    Route::get('/find-friends', [FriendController::class, 'index'])->name('friends.find');


    // folllower
    Route::post('/follow/{id}', [FollowController::class, 'toggle'])->name('follow.toggle');
    Route::get('/followers/{user}', [FollowController::class, 'followers'])
        ->name('followers.list');
    // Route::post('/follow/{id}', [FollowController::class, 'toggle'])->name('follow.toggle');

    Route::get('/follow-requests', [FollowController::class, 'requests'])
        ->name('follow.requests');

    Route::post('/follow-requests/{id}/accept', [FollowController::class, 'accept'])
        ->name('follow.accept');


    // message
    Route::get('/messages/{user}/ajax', [MessageController::class, 'ajaxMessages'])
        ->name('messages.ajax');

    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{user}', [MessageController::class, 'show'])->name('messages.show');

    Route::post('/messages/{user}', [MessageController::class, 'store'])->name('messages.store');
    Route::put('/messages/{message}', [MessageController::class, 'update'])->name('messages.update');
    Route::delete('/messages/{message}', [MessageController::class, 'destroy'])->name('messages.destroy');

    Route::post('/messages/{message}/like', [MessageController::class, 'like'])->name('messages.like');
    Route::get('/messages/ajax/friends', [MessageController::class, 'ajaxFriends'])->name('messages.ajax.friends');
Route::get('/messages/read-status', [MessageController::class, 'readStatus'])->name('messages.read-status');


});
