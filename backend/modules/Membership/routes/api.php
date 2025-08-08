<?php

use Illuminate\Support\Facades\Route;
use Modules\Membership\Http\Controllers\Api\V1\{
    UnionController, LocalController, MemberController
};

Route::prefix('api/v1')
    ->middleware(['auth:sanctum', 'throttle:60,1']) // simple rate limit per token
    ->group(function () {
        Route::middleware('module.permission:membership,viewer')->group(function () {
            Route::get('unions', [UnionController::class, 'index']);
            Route::get('unions/{union}', [UnionController::class, 'show']);
            Route::get('locals', [LocalController::class, 'index']);
            Route::get('locals/{local}', [LocalController::class, 'show']);
            Route::get('members', [MemberController::class, 'index']);
            Route::get('members/{member}', [MemberController::class, 'show']);
        });

        Route::middleware('module.permission:membership,editor')->group(function () {
            Route::post('unions', [UnionController::class, 'store']);
            Route::put('unions/{union}', [UnionController::class, 'update']);
            Route::delete('unions/{union}', [UnionController::class, 'destroy']);

            Route::post('locals', [LocalController::class, 'store']);
            Route::put('locals/{local}', [LocalController::class, 'update']);
            Route::delete('locals/{local}', [LocalController::class, 'destroy']);

            Route::post('members', [MemberController::class, 'store']);
            Route::put('members/{member}', [MemberController::class, 'update']);
            Route::delete('members/{member}', [MemberController::class, 'destroy']);
        });
    });
