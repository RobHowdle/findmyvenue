<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\VenueController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PromoterController;
use App\Http\Controllers\DashboardController;
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
Route::get('/promoter-suggestion', [VenueController::class, 'suggestPromoters'])->name('suggestPromoters');

Route::get('/promoters', [PromoterController::class, 'index'])->name('promoters');
Route::get('/promoters/filter', [PromoterController::class, 'filterCheckboxesSearch'])->name('promoters.filterCheckboxesSearch');
Route::get('/promoters/{id}', [PromoterController::class, 'show'])->name('promoter');
Route::post('/promoters/{id}/submitReview', [PromoterController::class, 'submitPromoterReview'])->name('submit-promoter-review');
Route::get('/other', [OtherServiceController::class, 'index'])->name('other');
Route::get('/other/{serviceName}', [OtherServiceController::class, 'showGroup'])->name('singleServiceGroup');
Route::get('/other/{serviceName}/{serviceId}', [OtherServiceController::class, 'show'])->name('singleService');

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');
Route::post('/dashboard/approve-promoter/{reviewId}', [DashboardController::class, 'approvePromoterReview'])->middleware(['auth', 'verified'])->name('pending-review-promoter.approve');
Route::post('/dashboard/display-promoter/{reviewId}', [DashboardController::class, 'displayPromoterReview'])->middleware(['auth', 'verified'])->name('pending-review-promoter.display');
Route::post('/dashboard/hide-promoter/{reviewId}', [DashboardController::class, 'hidePromoterReview'])->middleware(['auth', 'verified'])->name('pending-review-promoter.hide');
Route::post('/dashboard/approve-display-promoter/{reviewId}', [DashboardController::class, 'approveDisplayPromoterReview'])->middleware(['auth', 'verified'])->name('pending-review-promoter.approve-display');
Route::post('/dashboard/approve-display-venue/{reviewId}', [DashboardController::class, 'approveDisplayVenueReview'])->middleware(['auth', 'verified'])->name('pending-review-venue.approve-display');
Route::post('/dashboard/approve-venue/{reviewId}', [DashboardController::class, 'approveVenueReview'])->middleware(['auth', 'verified'])->name('pending-review-venue.approve');
Route::post('/dashboard/user-service-link', [DashboardController::class, 'userServiceLink'])->middleware(['auth', 'verified'])->name('user-service-link');


Route::middleware('auth')->group(function () {
    Route::post('/profile/{user}', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/{user}', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/admin/venues', [AdminController::class, 'getVenues'])->name('admin.venues');
    Route::post('/admin/venues', [AdminController::class, 'saveNewVenue'])->name('admin.new-venue');
    Route::get('/admin/venues/list', [AdminController::class, 'viewVenueList'])->name('admin.venue-list');
    Route::get('/admin/venues/{venueId}/edit', [AdminController::class, 'editVenue'])->name('admin.edit-venues');
    Route::post('/admin/venues/{venueId}', [AdminController::class, 'updateVenue'])->name('admin.update-venue');
    Route::delete('/admin/venues/{venueId}', [AdminController::class, 'deleteVenue'])->name('admin.delete-venue');

    Route::get('/admin/promoters', [AdminController::class, 'getPromoters'])->name('admin.promoters');
    Route::post('/admin/promoters', [AdminController::class, 'saveNewPromoter'])->name('admin.new-promoter');
    Route::get('/admin/promoters/list', [AdminController::class, 'viewPromoterList'])->name('admin.promoter-list');
    Route::get('/admin/promoters/{promoterId}/edit', [AdminController::class, 'editPromoter'])->name('admin.edit-promoter');
    Route::post('/admin/promoters/{promoterId}', [AdminController::class, 'updatePromoter'])->name('admin.update-promoter');
    Route::delete('/admin/promoters/{promoterId}', [AdminController::class, 'deletePromoter'])->name('admin.delete-promoter');
    Route::get('/admin/promoters/get-location-venues', [AdminController::class, 'getVenuesBySelectedLocation']);

    Route::get('/admin/create-other', [AdminController::class, 'createOtherService'])->name('admin.createOther');
    Route::post('/admin/save-other', [AdminController::class, 'saveNewOtherService'])->name('admin.save-other');
});

require __DIR__ . '/auth.php';
