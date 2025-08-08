<?php

use Illuminate\Support\Facades\Route;
use Modules\Payments\Http\Controllers\Api\V1\{InvoiceController, PaymentController};

Route::prefix('api/v1')
    ->middleware(['auth:sanctum', 'throttle:60,1', 'module.permission:payments,viewer'])
    ->group(function () {
        Route::get('invoices', [InvoiceController::class, 'index']);
        Route::get('invoices/{invoice}', [InvoiceController::class, 'show']);
        Route::get('payments', [PaymentController::class, 'index']);
        Route::get('payments/{payment}', [PaymentController::class, 'show']);
    });

Route::prefix('api/v1')
    ->middleware(['auth:sanctum', 'throttle:60,1', 'module.permission:payments,editor'])
    ->group(function () {
        Route::post('invoices', [InvoiceController::class, 'store']);
        Route::put('invoices/{invoice}', [InvoiceController::class, 'update']);
        Route::delete('invoices/{invoice}', [InvoiceController::class, 'destroy']);

        Route::post('payments', [PaymentController::class, 'store']);
        Route::put('payments/{payment}', [PaymentController::class, 'update']);
        Route::delete('payments/{payment}', [PaymentController::class, 'destroy']);
    });
