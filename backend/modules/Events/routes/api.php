<?php

use Illuminate\Support\Facades\Route;
use Modules\Events\Http\Controllers\Api\V1\EventController;

Route::prefix('api/v1')
    ->middleware(['auth:sanctum', 'throttle:60,1', 'module.permission:events,viewer'])
    ->group(function () {
        Route::get('events', [EventController::class, 'index']);
        Route::get('events/{event}', [EventController::class, 'show']);
    });

Route::prefix('api/v1')
    ->middleware(['auth:sanctum', 'throttle:60,1', 'module.permission:events,editor'])
    ->group(function () {
        Route::post('events', [EventController::class, 'store']);
        Route::put('events/{event}', [EventController::class, 'update']);
        Route::delete('events/{event}', [EventController::class, 'destroy']);
    });
