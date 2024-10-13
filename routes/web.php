<?php

use App\Models\OtherService;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\VenueController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PromoterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OtherServiceController;
use App\Http\Controllers\PromoterDashboardController;

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
})->name('welcome');

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
Route::get('/other/filter', [OtherServiceController::class, 'filterCheckboxesSearch'])->name('other.filterCheckboxesSearch');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/promoter', [PromoterDashboardController::class, 'index'])->name('promoter.dashboard');
    Route::get('/dashboard/promoter/users', [PromoterDashboardController::class, 'promoterUsers'])->name('promter.dashboard.users');
    Route::get('/dashboard/promoter/finances', [PromoterDashboardController::class, 'promoterFinances'])->name('promoter.dashboard.finances');
    Route::get('/dashboard/promoter/finances/new-budget', [PromoterDashboardController::class, 'createNewPromoterBudget'])->name('promoter.dashboard.finances.new');
    Route::post('/dashboard/promoter/finances/save-budget', [PromoterDashboardController::class, 'saveNewPromoterBudget'])->name('promoter.dashboard.finances.saveNew');
    Route::post('/dashboard/promoter/finances/export', [PromoterDashboardController::class, 'exportFinances'])->name('promoter.dashboard.finances.export');
    Route::get('/dashboard/promoter/finances/{id}', [PromoterDashboardController::class, 'showSingleFinance'])->name('promoter.dashboard.finances.show');
    Route::get('/dashboard/promoter/finances/{id}/edit', [PromoterDashboardController::class, 'editSingleFinance'])->name('promoter.dashboard.finances.edit');
    Route::patch('/dashboard/promoter/finances/{finance}', [PromoterDashboardController::class, 'updateSingleFinance'])->name('promoter.dashboard.finances.update');
    Route::post('/dashboard/promoter/finances/{finance}', [PromoterDashboardController::class, 'exportSingleFinanceRecord'])->name('promoter.dashboard.finances.exportSingleFinanceRecord');
    Route::get('/dashboard/promoter/todo-list', [PromoterDashboardController::class, 'showPromoterTodos'])->name('promoter.dashboard.todo-list');
    Route::get('/dashboard/promoter/todo-items', [PromoterDashboardController::class, 'getPromoterTodos'])->name('promoter.dashboard.todo-items');
    Route::post('/dashboard/promoter/todolist/new', [PromoterDashboardController::class, 'addNewTodoItem'])->name('promoter.dashboard.new-todo-item');
    Route::post('/dashboard/promoter/todo-item/{id}/complete', [PromoterDashboardController::class, 'completeTodoItem'])->name('promoter.dashboard.complete-todo-item');
    Route::delete('/dashboard/promoter/todo-item/{id}', [PromoterDashboardController::class, 'deleteTodoItem'])->name('promoter.dashboard.delete-todo-item');
    Route::get('/dashboard/promoter/todo-item/completed-items', [PromoterDashboardController::class, 'showCompletedTodoItems'])->name('promoter.dashboard.completed-todo-items');
    Route::get('/dashboard/promoter/search', [PromoterDashboardController::class, 'searchExistingPromoters'])->name('admin.dashboard.promoter.search');
    Route::post('/dashboard/promoter/link', [PromoterDashboardController::class, 'linkToExistingPromoter'])->name('admin.dashboard.promoter.link');
    Route::post('/dashboard/promoter/store', [PromoterDashboardController::class, 'storeNewPromoter'])->name('admin.dashboard.promoter.store');
    Route::get('/dashboard/promoter/users/new-user', [PromoterDashboardController::class, 'newUser'])->name('promoter.dashboard.users.new');
    Route::get('/dashboard/promoter/users/search-users', [PromoterDashboardController::class, 'searchUsers'])->name('admin.dashboard.promoter.search-users');
    Route::post('/dashboard/promoter/users/add-user', [PromoterDashboardController::class, 'addUserToCompany'])->name('admin.dashboard.promoter.add-user-to-company');
    Route::delete('/dashboard/promoter/delete-user', [PromoterDashboardController::class, 'deleteUserFromCompany'])->name('admin.dashboard.promoter.delete-user');
    Route::get('/dashboard/promoter/events', [PromoterDashboardController::class, 'showPromoterEvents'])->name('admin.dashboard.promoter.show-events');
    Route::get('/dashboard/promoter/events/create-event', [PromoterDashboardController::class, 'createNewPromoterEvent'])->name('admin.dashboard.promoter.create-new-event');
    Route::get('/dashboard/promoter/events/search-venues', [PromoterDashboardController::class, 'eventSelectVenue'])->name('admin.dashboard.promoter.search-venues');
    Route::post('/dashboard/promoter/events/store-event', [PromoterDashboardController::class, 'storeNewPromoterEvent'])->name('admin.dashboard.promoter.store-new-event');
    Route::get('/dashboard/promoter/events/{id}', [PromoterDashboardController::class, 'showSinglePromoterEvent'])->name('admin.dashboard.promoter.show-single-event');
    Route::delete('/dashboard/promoter/events/{id}', [PromoterDashboardController::class, 'deleteSinglePromoterEvent'])->name('admin.dashboard.promoter.delete-single-event');
    Route::get('/dashboard/promoter/events/load-more-upcoming', [PromoterDashboardController::class, 'loadMoreUpcomingEvents'])->name('admin.dashboard.promoter.load-more-upcoming-events');
    Route::get('/dashboard/promoter/events/load-more-past', [PromoterDashboardController::class, 'loadMorePastEvents'])->name('admin.dashboard.promoter.load-more-past-events');



    // Route::post('/dashboard/approve-promoter/{reviewId}', [DashboardController::class, 'approvePromoterReview'])->name('pending-review-promoter.approve');
    // Route::post('/dashboard/display-promoter/{reviewId}', [DashboardController::class, 'displayPromoterReview'])->name('pending-review-promoter.display');
    // Route::post('/dashboard/hide-promoter/{reviewId}', [DashboardController::class, 'hidePromoterReview'])->name('pending-review-promoter.hide');
    // Route::post('/dashboard/approve-display-promoter/{reviewId}', [DashboardController::class, 'approveDisplayPromoterReview'])->name('pending-review-promoter.approve-display');
    // Route::post('/dashboard/approve-display-venue/{reviewId}', [DashboardController::class, 'approveDisplayVenueReview'])->name('pending-review-venue.approve-display');
    // Route::post('/dashboard/approve-venue/{reviewId}', [DashboardController::class, 'approveVenueReview'])->name('pending-review-venue.approve');
    // Route::post('/dashboard/user-service-link', [DashboardController::class, 'userServiceLink'])->name('user-service-link');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile/{user}', [ProfileController::class, 'edit'])->name('profile.edit');
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
