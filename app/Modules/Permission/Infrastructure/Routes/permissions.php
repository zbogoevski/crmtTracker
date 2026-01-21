<?php

declare(strict_types=1);

use App\Modules\Permission\Infrastructure\Http\Controllers\PermissionController;
use Illuminate\Support\Facades\Route;

Route::prefix('api/v1/permissions')
    ->middleware(['auth:sanctum'])
    ->name('api.permissions.')
    ->group(function (): void {
        Route::get('/', [PermissionController::class, 'index'])
            ->middleware('throttle:60,1')
            ->name('index');

        Route::post('/', [PermissionController::class, 'store'])
            ->middleware('throttle:10,60')
            ->name('store');

        Route::get('/{id}', [PermissionController::class, 'show'])
            ->middleware('throttle:120,1')
            ->name('show');

        Route::put('/{id}', [PermissionController::class, 'update'])
            ->middleware('throttle:30,1')
            ->name('update');

        Route::patch('/{id}', [PermissionController::class, 'update'])
            ->middleware('throttle:30,1')
            ->name('patch');

        Route::delete('/{id}', [PermissionController::class, 'destroy'])
            ->middleware('throttle:10,60')
            ->name('destroy');
    });
