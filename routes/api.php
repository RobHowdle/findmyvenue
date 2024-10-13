<?php

use App\Http\Controllers\APIRequestsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VenueApiController;
use App\Http\Controllers\PromoterDashboardController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/venues/{id}', [VenueApiController::class, 'show']);
Route::get('/dashboard/promoter/finances', [PromoterDashboardController::class, 'getFinanceData']);
Route::get('/bands/search', [APIRequestsController::class, 'searchBands']);

Route::middleware(['auth', 'verified'])->get('/profile/{$id}/calendar', [APIRequestsController::class, 'getUserCalendarEvents']);
