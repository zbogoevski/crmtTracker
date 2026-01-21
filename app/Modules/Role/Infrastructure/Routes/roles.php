<?php

declare(strict_types=1);

use App\Modules\Role\Infrastructure\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;

Route::prefix('api/v1/roles')
    ->middleware(['auth:sanctum'])
    ->name('api.roles.')
    ->group(function (): void {
        Route::get('/', [RoleController::class, 'index'])
            ->middleware('throttle:60,1')
            ->name('index');

        Route::post('/', [RoleController::class, 'store'])
            ->middleware('throttle:10,60')
            ->name('store');

        Route::get('/{id}', [RoleController::class, 'show'])
            ->middleware('throttle:120,1')
            ->name('show');

        Route::put('/{id}', [RoleController::class, 'update'])
            ->middleware('throttle:30,1')
            ->name('update');

        Route::patch('/{id}', [RoleController::class, 'update'])
            ->middleware('throttle:30,1')
            ->name('patch');

        Route::delete('/{id}', [RoleController::class, 'destroy'])
            ->middleware('throttle:10,60')
            ->name('destroy');
    });
