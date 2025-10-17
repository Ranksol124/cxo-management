<?php

// dd('api.php loaded');
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;






Route::get('data-response',[ApiController::class, 'GetRecordAll']);

