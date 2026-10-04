<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UpdateThemeController;
use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home')->header('Home')->description('Welcome to Solaris');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit')->header('Edit profile')->description('Update your profile details');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/theme', UpdateThemeController::class)->name('theme.update');
});
