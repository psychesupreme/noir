<?php

use App\Http\Controllers\Api\ApiTokenController;
use App\Http\Controllers\Api\ApiCatalogController;
use App\Http\Controllers\Api\ApiCheckoutController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes - Noir & Bloom
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->middleware('throttle:60,1')->group(function () {
    // Health check
    Route::get('/status', fn () => response()->json([
        'status' => 'operational',
        'service' => 'Noir & Bloom API',
        'version' => '1.0.0',
    ]));

    // Authentication (Guest)
    Route::post('/auth/token', [ApiTokenController::class, 'issueToken']);

    // Catalog (Public/Guest)
    Route::get('/products', [ApiCatalogController::class, 'index']);
    Route::get('/products/{id}', [ApiCatalogController::class, 'show']);

    // Protected Routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/auth/logout', [ApiTokenController::class, 'revokeToken']);
        Route::post('/checkout', [ApiCheckoutController::class, 'checkout']);
    });
});

