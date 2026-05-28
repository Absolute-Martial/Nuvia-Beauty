<?php

use App\Domains\Beauty\Controllers\BeautyEventController;
use App\Domains\Beauty\Controllers\BeautyProductMappingController;
use App\Domains\Beauty\Controllers\BeautyRecommendationController;
use Illuminate\Support\Facades\Route;
use Marvel\Enums\Permission;

Route::get('beauty/product-mappings', [BeautyProductMappingController::class, 'index']);
Route::post('beauty/events', [BeautyEventController::class, 'store']);
Route::post('beauty/recommendations/generate', [BeautyRecommendationController::class, 'generate']);
Route::get('beauty/recommendations/{id}', [BeautyRecommendationController::class, 'show']);

Route::middleware(['auth:sanctum', 'email.verified'])->group(function () {
    Route::post('beauty/product-mappings', [BeautyProductMappingController::class, 'store']);
    Route::put('beauty/product-mappings/{id}', [BeautyProductMappingController::class, 'update']);

    Route::middleware(['permission:' . Permission::SUPER_ADMIN])->group(function () {
        Route::get('admin/beauty/product-mappings/overview', [BeautyProductMappingController::class, 'overview']);
        Route::post('admin/beauty/recommendations/recompute', [BeautyRecommendationController::class, 'recompute']);
    });
});
