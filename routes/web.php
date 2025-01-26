<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Documents\DocumentController;
use App\Http\Controllers\News\NewsController;
use App\Http\Controllers\usersController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

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




Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', action: [AuthController::class, 'login']);
Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');



Route::middleware(['isAdmin'])->group(function () {

    Route::get('index', [AuthController::class, 'showAdminDashboard'])->name('index');
    Route::get('dashboard', [AuthController::class, 'showMembreDashboard'])->name('membre');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('admin/user-connected', [AuthController::class, 'userConnected'])->name('user.connected');
    Route::get('list-users', [usersController::class, 'index'])->name('users.index');
    Route::get('list-users-archives', [usersController::class, 'listArchives'])->name('users.archives');
    Route::get('store-user', [UsersController::class, 'showStoreUsers'])->name('store-user');
    Route::post('create-user', [UsersController::class, 'store'])->name('create-user');
    Route::get('/users/{user}/edit', [UsersController::class, 'edit'])->name('users.edit');
    Route::get('/user/editConnectedUser', [UsersController::class, 'editConnectedUser'])->name(name: 'users.editConnectedUser');
    Route::patch('/users/{id}/restore', [UsersController::class, 'restore'])->name('users.restore');
    Route::put('users/{id}', [UsersController::class, 'update'])->name('users.update');
    Route::put('users/connected', [UsersController::class, 'updateUserConnected'])->name('users.connected');
    Route::delete('users/{id}', [UsersController::class, 'destroy'])->name('users.destroy');




});


Route::prefix('documents')->group(function () {
    Route::get('non-archived', [DocumentController::class, 'indexNonArchived'])->name('documents.non_archived');

    Route::get('archived', [DocumentController::class, 'indexArchived'])->name('documents.archived');

    Route::post('store', [DocumentController::class, 'store'])->name('documents.store');

    Route::put('update/{id}', [DocumentController::class, 'update'])->name('documents.update');

    Route::delete('delete/{id}', [DocumentController::class, 'delete'])->name('documents.delete');

    Route::post('restore/{id}', [DocumentController::class, 'restore'])->name('documents.restore');

    Route::get('create-document', [DocumentController::class, 'showStoreDocument'])->name('store-document');
    Route::get('/{id}/edit', [DocumentController::class, 'edit'])->name('documents.edit');

});

Route::prefix('news')->group(function () {
  
    Route::get('create', [NewsController::class, 'showStoreNews'])->name('store-news');
    Route::post('store', [NewsController::class, 'store'])->name('news.store');
    Route::get('{news}/edit', [NewsController::class, 'edit'])->name('news.edit');
    Route::put('{news}', [NewsController::class, 'update'])->name('news.update');
    Route::get('/', [NewsController::class, 'index'])->name('news.index');
    Route::get('archived', [NewsController::class, 'indexArchived'])->name('news.archived');
    Route::delete('delete/{id}', [NewsController::class, 'delete'])->name('news.delete');
    Route::post('restore/{id}', [NewsController::class, 'restore'])->name('news.restore');


});






