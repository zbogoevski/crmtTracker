<?php

declare(strict_types=1);

use App\Modules\FakeNonExistentModule\Infrastructure\Http\Controllers\FakeNonExistentModuleController;
use Illuminate\Support\Facades\Route;

Route::prefix('api/v1/test_modules')->middleware(['auth:sanctum'])->group(function (): void {
    Route::get('/', [FakeNonExistentModuleController::class, 'index'])
        ->middleware('throttle:120,1');

    Route::post('/', [FakeNonExistentModuleController::class, 'store'])
        ->middleware('throttle:20,60');

    Route::get('/{id}', [FakeNonExistentModuleController::class, 'show'])
        ->middleware('throttle:120,1');

    Route::put('/{id}', [FakeNonExistentModuleController::class, 'update'])
        ->middleware('throttle:20,60');

    Route::delete('/{id}', [FakeNonExistentModuleController::class, 'destroy'])
        ->middleware('throttle:5,60');
});
