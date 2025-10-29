<?php

// dd('api.php loaded');
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Middleware\ApiPublicRestriction;
use App\Http\Controllers\FeedController;
use App\Events\Spotlight;
Route::get('data-response', [ApiController::class, 'GetRecordAll']);



Route::middleware(['auth:sanctum'])->group(function () {    
    Route::post('/auth/update-info', [ApiController::class, 'updateProfile']);
    Route::post('/auth/password-update', [ApiController::class, 'updatePassword']);
    Route::post('/auth/password-reset', [ApiController::class, 'passwordReset']);
    Route::post('/auth/apply-job', [ApiController::class, 'ApplyJob']);
    Route::post('/auth/book-event', [ApiController::class, 'BookEvent']);
    Route::post('/{feed}/like', [FeedController::class, 'like'])->name('feed.like');
});




Route::middleware(['ApiPublicRestriction'])->group(function () {

    Route::post('/{feed}/comment', [FeedController::class, 'comment'])->name('feed.comment');
    Route::get('/{feed}/get-comments', [FeedController::class, 'getComments']);

});

