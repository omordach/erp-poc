<?php

use Illuminate\Support\Facades\Route;
use Modules\Grievances\Http\Controllers\Api\V1\GrievanceController;

Route::prefix('api/v1')
    ->middleware(['auth:sanctum', 'throttle:60,1', 'module.permission:grievances,viewer'])
    ->group(function () {
        Route::get('grievances', [GrievanceController::class, 'index']);
        Route::get('grievances/{grievance}', [GrievanceController::class, 'show']);
    });

Route::prefix('api/v1')
    ->middleware(['auth:sanctum', 'throttle:60,1', 'module.permission:grievances,editor'])
    ->group(function () {
        Route::post('grievances', [GrievanceController::class, 'store']);
        Route::put('grievances/{grievance}', [GrievanceController::class, 'update']);
        Route::delete('grievances/{grievance}', [GrievanceController::class, 'destroy']);
    });
