<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CommentController;

Route::prefix('comments')
    ->name('comment.')
    ->namespace('App\Http\Controllers')
    ->group(function () {
        Route::get('/', [CommentController::class, 'index'])->name('index');

        Route::get('/{comment}', [CommentController::class, 'show'])->name('show')
            ->whereNumber('comment');

        Route::post('/', [CommentController::class, 'store'])->name('store');

        Route::patch('/{comment}', [CommentController::class, 'update'])->name('update');

        Route::delete('/{comment}', [CommentController::class, 'destroy'])->name('destroy');
});