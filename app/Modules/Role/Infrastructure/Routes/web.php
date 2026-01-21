<?php

declare(strict_types=1);

use App\Modules\Role\Infrastructure\Http\Controllers\WebRoleController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth'])->group(function (): void {
    Route::prefix('roles')->name('web.roles.')->group(function (): void {
        Route::get('/', [WebRoleController::class, 'index'])->name('index');
        Route::get('/create', [WebRoleController::class, 'create'])->name('create');
        Route::post('/', [WebRoleController::class, 'store'])->name('store');
        Route::get('/{id}', [WebRoleController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [WebRoleController::class, 'edit'])->name('edit');
        Route::put('/{id}', [WebRoleController::class, 'update'])->name('update');
        Route::delete('/{id}', [WebRoleController::class, 'destroy'])->name('destroy');
    });
});
