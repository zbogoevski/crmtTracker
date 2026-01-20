<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Redirect root to login if not authenticated, otherwise to dashboard
Route::get('/', function () {
    if (Auth::check()) {
        // Redirect to dashboard if authenticated
        return redirect('/dashboard');
    }

    return redirect()->route('login');
});

// Dashboard route (protected)
Route::middleware(['web', 'auth'])->group(function (): void {
    Route::get('/dashboard', function () {
        return view('dashboard.index', [
            'groups' => collect([]),
            'activeGroup' => null,
            'overallAverage' => 0,
            'competitorCount' => 0,
            'alertsCount' => 0,
            'alerts' => collect([]),
            'scores' => [],
        ]);
    })->name('dashboard');
});

// Password reset route for Laravel's built-in password reset functionality
Route::get('/password/reset/{token}', fn () => response()->json(['message' => 'Password reset page']))->name('password.reset');
