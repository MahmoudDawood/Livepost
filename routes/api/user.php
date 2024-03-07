<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::prefix('users')
    ->name('user.')
    ->namespace('App\Http\Controllers')
    ->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');

        Route::post('/', [UserController::class, 'store'])->name('store');

        Route::get('/{user}', [UserController::class, 'show'])->name('show')
            ->whereNumber('user');

        Route::patch('/{user}', [UserController::class, 'update'])->name('update');

        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
});