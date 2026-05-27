<?php

use App\Domains\Beauty\Controllers\BeautyProductMappingController;
use App\Domains\Beauty\Controllers\BeautyRecommendationController;
use Illuminate\Support\Facades\Route;

Route::get('beauty/product-mappings', [BeautyProductMappingController::class, 'index']);
Route::post('beauty/recommendations/generate', [BeautyRecommendationController::class, 'generate']);
Route::get('beauty/recommendations/{id}', [BeautyRecommendationController::class, 'show']);

Route::middleware(['auth:sanctum', 'email.verified'])->group(function () {
    Route::post('beauty/product-mappings', [BeautyProductMappingController::class, 'store']);
    Route::put('beauty/product-mappings/{id}', [BeautyProductMappingController::class, 'update']);
});
