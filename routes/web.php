<?php

use App\Http\Controllers\MemberVerificationController;
use App\Http\Controllers\HomeController;
use App\Models\Page;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\PaymentController;
use App\Filament\Pages\PaymentCheckout;


// Route::get('/', function () {
//     $page = Page::where('slug', 'home')->firstOrFail();
//     return view('welcome', compact('page'));
//     // return view('welcome');
// });

// Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/member/verify', [MemberVerificationController::class, 'verify'])->name('member.verify');


// routes/web.php


Route::post('/feed/{feed}/like', [FeedController::class, 'like'])->name('feed.like');
Route::post('/feed/{feed}/dislike', [FeedController::class, 'dislike'])->name('feed.dislike');
Route::post('/feed/{feed}/comment', [FeedController::class, 'comment'])->name('feed.comment');
Route::get('/feed/{feed}/comments', [FeedController::class, 'getComments']);
Route::delete('/member-feeds/{id}', [FeedController::class, 'destroy'])
    ->name('member-feeds.destroy');
Route::patch('/member-feeds/{id}/unpublish', [FeedController::class, 'unpublish'])
    ->name('member-feeds.unpublish');


Route::get('/portal/payment/{id}', [PaymentController::class, 'index']);
Route::post('portal/payment/{plan}', [PaymentController::class, 'stripePost'])->name('stripe.post');


Route::get('/portal/payment-checkout/{planId}', PaymentCheckout::class)
    ->name('filament.pages.payment-checkout');

