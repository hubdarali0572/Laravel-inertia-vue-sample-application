<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\FacebookAuthController;

require __DIR__.'/public-site.php';

Route::post('/locale', [LocaleController::class, 'update'])->name('locale.update');


// Google & Facebook Login (auth group ke BAHAR)
Route::middleware('guest')->group(function () {
    Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('google.login');
});
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('google.callback');
Route::get('/auth/facebook/redirect', [FacebookAuthController::class, 'redirect'])->name('facebook.redirect');
Route::get('/auth/facebook/callback', [FacebookAuthController::class, 'callback'])->name('facebook.callback');


// OTHER ROUTES
Route::middleware('auth')->group(function () {
    Route::get('/media/{media}/{conversion?}', [MediaController::class, 'show'])
        ->name('media.show');

    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->middleware('verified')->name('dashboard');

    Route::resource('users', UserController::class);
    Route::resource('roles', RoleController::class);

    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity.index');
    Route::get('/activity-logs/{id}', [ActivityLogController::class, 'show'])->name('activity.show');
    Route::delete('/activity-logs/{id}', [ActivityLogController::class, 'destroy'])->name('activity.destroy');
    Route::delete('/activity-logs-clear', [ActivityLogController::class, 'clearAll'])->name('activity.clear');
    Route::get('/api/activities/{module}/{id}', [ActivityLogController::class, 'getLogs']);

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
