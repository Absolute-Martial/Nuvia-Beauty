<?php

use App\Domains\Storage\Controllers\StorageController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'email.verified'])->group(function () {
    Route::post('storage/upload-slots', [StorageController::class, 'uploadSlots']);
    Route::post('storage/media/{mediaId}/confirm', [StorageController::class, 'confirm']);
    Route::get('storage/media/{mediaId}/download-url', [StorageController::class, 'downloadUrl']);
    Route::delete('storage/media/{mediaId}', [StorageController::class, 'destroy']);
});
