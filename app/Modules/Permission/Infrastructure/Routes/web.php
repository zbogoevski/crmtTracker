<?php

declare(strict_types=1);

use App\Modules\Permission\Infrastructure\Http\Controllers\WebPermissionController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth'])->group(function (): void {
    Route::prefix('permissions')->name('permissions.')->group(function (): void {
        Route::get('/', [WebPermissionController::class, 'index'])->name('index');
        Route::get('/create', [WebPermissionController::class, 'create'])->name('create');
        Route::post('/', [WebPermissionController::class, 'store'])->name('store');
        Route::get('/{id}', [WebPermissionController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [WebPermissionController::class, 'edit'])->name('edit');
        Route::put('/{id}', [WebPermissionController::class, 'update'])->name('update');
        Route::delete('/{id}', [WebPermissionController::class, 'destroy'])->name('destroy');
    });
});
