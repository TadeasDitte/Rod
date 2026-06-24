<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OauthController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::prefix('{locale?}')->group(function () {
    Route::inertia('/', 'Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ])->name('home');
    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('dashboard', DashboardController::class)->name('dashboard');
    });
});

Route::middleware('web')->group(function () {
    Route::get('auth/{provider}/redirect', [OauthController::class, 'redirect'])
        ->name('oauth.redirect');
    Route::get('auth/{provider}/callback', [OauthController::class, 'callback'])
        ->name('oauth.callback');

    Route::middleware('auth')->group(function () {
        Route::delete('auth/{provider}/unlink', [OauthController::class, 'destroy'])
            ->name('oauth.unlink');
    });
});

require __DIR__.'/settings.php';
