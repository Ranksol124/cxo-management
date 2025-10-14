<?php

// dd('api.php loaded');
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MemberFeedController;




Route::get('/test-api', function (Request $request) {
    return response()->json([
        'success' => true,
        'message' => 'API route is working!',
    ]);
});
