<?php

declare(strict_types=1);

use App\Modules\Auth\Infrastructure\Http\Controllers\WebAuthController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'guest'])->group(function (): void {
    Route::get('/login', [WebAuthController::class, 'show'])->name('web.login');
    Route::post('/login', [WebAuthController::class, 'store']);

    // Compatibility alias for Laravel defaults (route('login'))
    Route::get('/auth/login', [WebAuthController::class, 'show'])->name('login');
});

Route::middleware(['web', 'auth'])->group(function (): void {
    Route::post('/logout', [WebAuthController::class, 'destroy'])->name('web.logout');

    // Compatibility alias for Laravel defaults (route('logout'))
    Route::post('/auth/logout', [WebAuthController::class, 'destroy'])->name('logout');
});
