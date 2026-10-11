<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatGroupeController;
use App\Http\Controllers\CompanyInfoController;
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

Route::get('/', function () {
    return redirect()->route(auth()->check() ? 'index' : 'login');
});

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
    Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {

    Route::get('index', [AuthController::class, 'showAdminDashboard'])->name('index');
    Route::get('dashboard', [AuthController::class, 'showMembreDashboard'])->name('membre');

    // ----------------------------- Administration des utilisateurs
    Route::middleware(['isAdmin'])->group(function () {
        Route::get('list-users', [usersController::class, 'index'])->name('users.index');
        Route::get('list-users-archives', [usersController::class, 'listArchives'])->name('users.archives');
        Route::get('store-user', [usersController::class, 'showStoreUsers'])->name('store-user');
        Route::post('create-user', [usersController::class, 'store'])->name('create-user');
        Route::post('/users/{id}/restore', [usersController::class, 'restore'])->whereNumber('id')->name('users.restore');

        Route::get('/users/{user}/edit', [usersController::class, 'edit'])->whereNumber('user')->name('users.edit');
        Route::get('/users/{user}/edit/professionnelle', [usersController::class, 'editProfessionnelle'])->whereNumber('user')->name('users.edit.professionnelle');
        Route::get('/users/{user}/edit/biography', [usersController::class, 'editBiography'])->whereNumber('user')->name('users.edit.biography');
        Route::put('users/{id}', [usersController::class, 'update'])->whereNumber('id')->name('users.update');
        Route::put('users/professionnelle/{id}', [usersController::class, 'updateProfessionnelleUser'])->whereNumber('id')->name('users.update.professionnelle');
        Route::put('users/biography/{id}', [usersController::class, 'updateBiographyUser'])->whereNumber('id')->name('users.update.biography');

        Route::post('/users/{id}/reset-password', [usersController::class, 'resetPassword'])->whereNumber('id')->name('users.reset_password');
        Route::delete('users/{id}', [usersController::class, 'destroy'])->whereNumber('id')->name('users.destroy');
        Route::get('admin/user-connected', [AuthController::class, 'userConnected'])->name('user.connected');

        // Coordonnées de l'entreprise
        Route::get('admin/coordonnees', [CompanyInfoController::class, 'edit'])->name('company.edit');
        Route::put('admin/coordonnees', [CompanyInfoController::class, 'update'])->name('company.update');
    });

    // ----------------------------- Profil de l'utilisateur connecté (admin ou membre)
    Route::get('/user/editConnectedUser', [usersController::class, 'editConnectedUser'])->name('users.editConnectedUser');
    Route::get('/user/edit-connected-user', [usersController::class, 'editConnectedUser'])->name('users.edit.connected.user');
    Route::get('/users/editConnectedUser/professionnelle', [usersController::class, 'editConnectedProfessionnel'])->name('users.connected.edit.professionnelle');
    Route::get('/users/editConnectedUser/biography', [usersController::class, 'editConnectedUserBiography'])->name('users.connected.edit.biography');

    Route::put('users/connected', [usersController::class, 'updateUserConnected'])->name('users.connected.update');
    Route::put('users/professionnelle', [usersController::class, 'updateUserProfessionnelle'])->name('users.connected.update.professionnelle');
    Route::put('users/biography', [usersController::class, 'updateUserBiography'])->name('users.connected.update.biography');
    Route::get('users/security', [usersController::class, 'editSecurity'])->name('users.edit.security');
    Route::put('users/update-password', [usersController::class, 'updatePassword'])->name('users.update.password');

    // ----------------------------- Première connexion membre
    Route::get('/user/edit-membre-profile/{id}', [usersController::class, 'editMembreProfile'])->whereNumber('id')->name('user.edit.connected.membre.user');
    Route::post('/user/update-membre-profile/{id}', [usersController::class, 'firstConnectionPersonnelle'])->whereNumber('id')->name('user.update.connected.membre.user');

    Route::get('/user/first-connection/personnelle', [usersController::class, 'editFirstConnectionPersonnelle'])->name('user.edit.personnel.membre.first');
    Route::get('/user/first-connection/professionnelle', [usersController::class, 'editFirstConnectionProfessionnelle'])->name('user.edit.professionnelle.membre.first');
    Route::get('/user/first-connection/biography', [usersController::class, 'editFirstConnectionBiography'])->name('user.edit.biography.membre.first');

    Route::put('/user/first-connection/personnelle', [usersController::class, 'firstConnectionPersonnelle'])->name('user.update.personnel.membre.first');
    Route::post('/user/first-connection/professionnelle', [usersController::class, 'firstConnectionProfessionnelle'])->name('user.update.professionnelle.membre.first');
    Route::post('/user/first-connection/biography', [usersController::class, 'firstConnectionBiography'])->name('user.update.biography.membre.first');

    // ----------------------------- Documents
    Route::prefix('documents')->group(function () {
        Route::get('non-archived', [DocumentController::class, 'indexNonArchived'])->name('documents.non_archived');
        Route::get('archived', [DocumentController::class, 'indexArchived'])->name('documents.archived');
        Route::post('store', [DocumentController::class, 'store'])->name('documents.store');
        Route::put('update/{id}', [DocumentController::class, 'update'])->whereNumber('id')->name('documents.update');
        Route::delete('delete/{id}', [DocumentController::class, 'delete'])->whereNumber('id')->name('documents.delete');
        Route::post('restore/{id}', [DocumentController::class, 'restore'])->whereNumber('id')->name('documents.restore');
        Route::get('create-document', [DocumentController::class, 'showStoreDocument'])->name('store-document');
        Route::get('/{id}/edit', [DocumentController::class, 'edit'])->whereNumber('id')->name('documents.edit');
    });

    // ----------------------------- Actualités / événements
    Route::prefix('news')->group(function () {
        Route::get('create', [NewsController::class, 'showStoreNews'])->name('store-news');
        Route::post('store', [NewsController::class, 'store'])->name('news.store');
        Route::get('archived', [NewsController::class, 'indexArchived'])->name('news.archived');
        Route::get('/', [NewsController::class, 'index'])->name('news.index');
        Route::get('{news}/edit', [NewsController::class, 'edit'])->whereNumber('news')->name('news.edit');
        Route::put('{news}', [NewsController::class, 'update'])->whereNumber('news')->name('news.update');
        Route::delete('delete/{id}', [NewsController::class, 'delete'])->whereNumber('id')->name('news.delete');
        Route::post('restore/{id}', [NewsController::class, 'restore'])->whereNumber('id')->name('news.restore');
    });

    // ----------------------------- Chat
    Route::get('/chat', [ChatGroupeController::class, 'index'])->name('chat.index');
    Route::post('/messages', [ChatGroupeController::class, 'sendMessage']);
    Route::get('/messages', [ChatGroupeController::class, 'fetchMessages']);
});
