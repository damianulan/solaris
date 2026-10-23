<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SuspendedUserController;
use App\Http\Controllers\UpdateThemeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::get('/reset-password/{token}', [AuthController::class, 'resetPasswordPage'])->middleware('guest')->name('password.reset');
    Route::middleware('nexus.auth')->group(function () {
        Route::get('/', [HomeController::class, 'index'])->name('home')->header('Home')->description('Welcome to Solaris');
        Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit')->header('Edit profile')->description('Update your profile details');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit')->header('Edit user')->description('Update user details');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::post('/users/{user}/suspension', [SuspendedUserController::class, 'store'])->name('users.suspensions.store');
    });

    Route::put('/theme', UpdateThemeController::class)->name('theme.update');
});
