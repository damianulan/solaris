<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\UpdateThemeController;
use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::put('/theme', UpdateThemeController::class)->name('theme.update');
});
