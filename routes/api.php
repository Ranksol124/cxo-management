<?php

// dd('api.php loaded');
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Middleware\ApiPublicRestriction;


Route::get('data-response', [ApiController::class, 'GetRecordAll']);



Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/auth/update-info', [ApiController::class, 'updateProfile']);
    Route::post('/auth/password-update', [ApiController::class, 'updatePassword']);
});
