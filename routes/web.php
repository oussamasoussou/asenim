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


Route::get('index', [AuthController::class, 'showAdminDashboard'])->name('index');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['isAdmin'])->group(function () {

    Route::get('dashboard', [AuthController::class, 'showMembreDashboard'])->name('membre');

    Route::get('list-users', [usersController::class, 'index'])->name('users.index');
    Route::get('list-users-archives', [usersController::class, 'listArchives'])->name('users.archives');
    Route::get('store-user', [UsersController::class, 'showStoreUsers'])->name('store-user');
    Route::post('create-user', [UsersController::class, 'store'])->name('create-user');
    Route::patch('/users/{id}/restore', [UsersController::class, 'restore'])->name('users.restore');
    


   
    
    Route::get('/users/{user}/edit', [UsersController::class, 'edit'])->name('users.edit');
    Route::get('/users/{user}/edit/professionnelle', [UsersController::class, 'editProfessionnelle'])->name('users.edit.professionnelle');
    Route::get('/users/{user}/edit/biography', [UsersController::class, 'editBiography'])->name('users.edit.biography');
    Route::put('users/{id}', [UsersController::class, 'update'])->name('users.update');
    Route::put('users/professionnelle/{id}', [UsersController::class, 'updateProfessionnelleUser'])->name('users.update.professionnelle');
    Route::put('users/biography/{id}', [UsersController::class, 'updateBiographyUser'])->name('users.update.biography');


    Route::put('users/connected', [UsersController::class, 'updateUserConnected'])->name('users.connected');
    Route::delete('users/{id}', [UsersController::class, 'destroy'])->name('users.destroy');

});











Route::get('/user/edit-connected-user', [UsersController::class, 'editConnectedUser'])->name( 'users.edit.connected.user');
Route::get('/users/edit/professionnelle', [UsersController::class, 'editConnectedProfessionnel'])->name('users.connected.edit.professionnelle');
Route::get('/users/edit/biography', [UsersController::class, 'editConnectedUserBiography'])->name('users.connected.edit.biography');

Route::put('users', [UsersController::class, 'updateUserConnected'])->name('users.connected.update');
Route::put('users/professionnelle', [UsersController::class, 'updateUserProfessionnelle'])->name('users.connected.update.professionnelle');
Route::put('users/biography', [UsersController::class, 'updateUserBiography'])->name('users.connected.update.biography');
















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


Route::get('/user/edit-membre-profile/{id}', [UsersController::class, 'editMembreProfile'])->name('user.edit.connected.membre.user');
Route::post('/user/update-membre-profile/{id}', [UsersController::class, 'firstConnectionPersonnelle'])->name('user.update.connected.membre.user');





Route::get('/user/edit-connected-user', [UsersController::class, 'editFirstConnectionPersonnelle'])->name(name: 'user.edit.personnel.membre.first');
Route::get('/users/edit/professionnelle', [UsersController::class, 'editFirstConnectionProfessionnelle'])->name('user.edit.professionnelle.membre.first');
Route::get('/users/edit/biography', [UsersController::class, 'editFirstConnectionBiography'])->name('user.edit.biography.membre.first');

Route::put('users', [UsersController::class, 'firstConnectionPersonnelle'])->name('user.update.personnel.membre.first');
Route::post('users/professionnelle', [UsersController::class, 'firstConnectionProfessionnelle'])->name('user.update.professionnelle.membre.first');
Route::post('users/biography', [UsersController::class, 'firstConnectionBiography'])->name('user.update.biography.membre.first');


