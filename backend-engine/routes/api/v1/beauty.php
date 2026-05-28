<?php

use App\Domains\Beauty\Controllers\BeautyEventController;
use App\Domains\Beauty\Controllers\BeautyProductMappingController;
use App\Domains\Beauty\Controllers\BeautyRecommendationController;
use App\Domains\Beauty\Controllers\BeautySessionController;
use Illuminate\Support\Facades\Route;
use Marvel\Enums\Permission;

Route::get('beauty/product-mappings', [BeautyProductMappingController::class, 'index']);
Route::post('beauty/events', [BeautyEventController::class, 'store']);
Route::post('beauty/recommendations/generate', [BeautyRecommendationController::class, 'generate']);
Route::get('beauty/recommendations/{id}', [BeautyRecommendationController::class, 'show']);

Route::middleware(['auth:sanctum', 'email.verified'])->group(function () {
    Route::post('beauty/product-mappings', [BeautyProductMappingController::class, 'store']);
    Route::put('beauty/product-mappings/{id}', [BeautyProductMappingController::class, 'update']);
    Route::post('beauty/sessions', [BeautySessionController::class, 'store']);
    Route::get('beauty/sessions/{id}', [BeautySessionController::class, 'show']);
    Route::post('beauty/sessions/{id}/attach-media', [BeautySessionController::class, 'attachMedia']);
    Route::post('beauty/sessions/{id}/analysis/start', [BeautySessionController::class, 'startAnalysis']);
    Route::post('beauty/sessions/{id}/save', [BeautySessionController::class, 'save']);
    Route::post('beauty/sessions/{id}/discard', [BeautySessionController::class, 'discard']);
    Route::get('beauty/sessions/{id}/recommendations', [BeautySessionController::class, 'recommendations']);
    Route::get('beauty/analysis/{taskId}/status', [BeautySessionController::class, 'analysisStatus']);

    Route::middleware(['permission:' . Permission::SUPER_ADMIN])->group(function () {
        Route::get('admin/beauty/product-mappings/overview', [BeautyProductMappingController::class, 'overview']);
        Route::post('admin/beauty/recommendations/recompute', [BeautyRecommendationController::class, 'recompute']);
    });
});
