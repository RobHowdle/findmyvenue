<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\VenueController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PromoterController;
use App\Http\Controllers\OtherServiceController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/venues', [VenueController::class, 'index'])->name('venues');
Route::get('/venues/filter', [VenueController::class, 'filterCheckboxesSearch'])->name('venues.filterCheckboxesSearch');
Route::get('/venues/filterByCoordinates', [VenueController::class, 'filterByCoordinates'])
     ->name('venues.filterByCoordinates');
Route::post('/venues/{id}/submitReview', [VenueController::class, 'submitVenueReview'])->name('submit-venue-review');
Route::get('/venues/{id}', [VenueController::class, 'show'])->name('venue');

Route::get('/promoters', [PromoterController::class, 'index'])->name('promoters');
Route::get('/promoters/{id}', [PromoterController::class, 'show'])->name('promoter');
Route::post('/promoters/{id}/submitReview', [PromoterController::class, 'submitPromoterReview'])->name('submit-promoter-review');
Route::get('/other', [OtherServiceController::class, 'index'])->name('other');


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/admin/venues', [AdminController::class, 'getVenues'])->name('admin.venues');
    Route::post('/admin/venues', [AdminController::class, 'saveNewVenue'])->name('admin.new-venue');
    Route::get('/admin/promoters', [AdminController::class, 'getPromoters'])->name('admin.promoters');
    Route::post('/admin/promoters', [AdminController::class, 'saveNewPromoter'])->name('admin.new-promoter');
});

require __DIR__.'/auth.php';