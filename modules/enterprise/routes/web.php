<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::get('/enterprise', [HomeController::class, 'index'])->name('enterprise.index');
});
