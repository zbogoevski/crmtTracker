<?php

declare(strict_types=1);

use App\Modules\User\Infrastructure\Http\Controllers\WebUserController;
use Illuminate\Support\Facades\Route;

// Profile routes (authenticated users)
Route::middleware(['web', 'auth'])->group(function (): void {
    Route::get('/profile', [WebUserController::class, 'showProfile'])->name('web.profile.show');
    Route::put('/profile', [WebUserController::class, 'updateProfile'])->name('web.profile.update');
    Route::put('/profile/password', [WebUserController::class, 'updatePassword'])->name('web.profile.password.update');
    Route::post('/profile/2fa/setup', [WebUserController::class, 'setupTwoFactor'])->name('web.profile.2fa.setup');
    Route::delete('/profile/2fa/disable', [WebUserController::class, 'disableTwoFactor'])->name('web.profile.2fa.disable');
});

// Users management routes (admin only - authorization checked in controller)
Route::middleware(['web', 'auth'])->prefix('users')->name('web.users.')->group(function (): void {
    Route::get('/', [WebUserController::class, 'index'])->name('index');
    Route::get('/create', [WebUserController::class, 'create'])->name('create');
    Route::post('/', [WebUserController::class, 'store'])->name('store');
    Route::get('/{id}', [WebUserController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [WebUserController::class, 'edit'])->name('edit');
    Route::put('/{id}', [WebUserController::class, 'update'])->name('update');
    Route::delete('/{id}', [WebUserController::class, 'destroy'])->name('destroy');
    Route::post('/{id}/2fa/toggle', [WebUserController::class, 'toggleTwoFactor'])->name('2fa.toggle');
});
