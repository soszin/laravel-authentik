<?php

use Illuminate\Support\Facades\Route;
use Soszin\LaravelAuthentik\Controllers\AuthentikController;

Route::middleware(['web'])->group(function () {
    Route::get('auth/redirect', [AuthentikController::class, 'redirectToProvider'])
        ->name('authentik.redirect');
    Route::get('auth/callback', [AuthentikController::class, 'callback'])
        ->name('authentik.callback');
});

