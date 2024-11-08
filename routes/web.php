<?php

use App\Models\OtherService;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\VenueController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\GigGuideController;
use App\Http\Controllers\PromoterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LinkedUserController;
use App\Http\Controllers\APIRequestsController;
use App\Http\Controllers\BandJourneyController;
use App\Http\Controllers\OtherServiceController;
use App\Http\Controllers\VenueJourneyController;
use App\Http\Controllers\BandDashboardController;
use App\Http\Controllers\VenueDashboardController;
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

Route::get('/gig-guide', [GigGuideController::class, 'showGigGuide'])->name('gig-guide');
Route::get('/gigs/filter', [GigGuideController::class, 'filterGigs'])->name('gigs.filter');

Route::middleware(['auth', 'web', 'verified'])->group(function () {
    // Dashboards - Main Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    // Dashboards - Role Based Dashboards
    Route::prefix('/dashboard')->group(function () {
        Route::get('/{dashboardType}', function ($dashboardType) {
            // Determine the appropriate controller based on the dashboard type
            $controllerName = ucfirst($dashboardType) . 'DashboardController';

            // Check if the controller class exists
            if (class_exists("App\\Http\\Controllers\\$controllerName")) {
                // Create an instance of the controller and call the index method
                return app("App\\Http\\Controllers\\$controllerName")->index($dashboardType);
            }

            // Handle the case where the controller does not exist
            abort(404);
        })->name('dashboard');
    });

    Route::post('/dashboard/notes/store-note', [DashboardController::class, 'storeNewNote'])->name('dashboard.store-new-note');

    // First Time User Routes
    Route::prefix('/{dashboardType}')->middleware(['auth'])->group(function () {
        Route::get('/band-journey', [BandJourneyController::class, 'index'])->name('band.journey');
        Route::get('/band-search', [BandJourneyController::class, 'search'])->name('band.search');
        Route::post('/band-journey/join/{id}', [BandJourneyController::class, 'joinBand'])->name('band.join');
        Route::post('/band-journey/create', [BandJourneyController::class, 'createBand'])->name('band.create');
    });

    Route::prefix('/{dashboardType}')->middleware(['auth'])->group(function () {
        Route::get('/users/search', [PromoterDashboardController::class, 'searchExistingPromoters'])->name('admin.dashboard.promoter.search');
        Route::post('/link', [PromoterDashboardController::class, 'linkToExistingPromoter'])->name('admin.dashboard.promoter.link');
        Route::post('/store', [PromoterDashboardController::class, 'storeNewPromoter'])->name('admin.dashboard.promoter.store');
    });

    Route::prefix('/{dashboardType}')->middleware(['auth'])->group(function () {
        Route::get('/venue-journey', [VenueJourneyController::class, 'index'])->name('venue.journey');
        Route::get('/venue-search', [VenueJourneyController::class, 'searchVenue'])->name('venue.search');
        Route::get('/venue-select', [VenueJourneyController::class, 'selectVenue'])->name('venue.select');
        Route::post('/venue-journey/link/{id}', [VenueJourneyController::class, 'linkVenue'])->name('venue.link');
        Route::post('/venue/create', [VenueJourneyController::class, 'createVenue'])->name('venue.store');
    });

    // Finances
    Route::prefix('/dashboard/{dashboardType}')->group(function () {
        Route::get('/finances', [FinanceController::class, 'showFinances'])->name('admin.dashboard.show-finances');
        Route::get('/finances/new-budget', [FinanceController::class, 'createFinance'])->name('admin.dashboard.create-new-finance');
        Route::post('/finances/save-budget', [FinanceController::class, 'storeFinance'])->name('admin.dashboard.store-new-finance');
        Route::post('/finances/export', [FinanceController::class, 'exportFinances'])->name('admin.dashboard.finances.export');
        Route::get('/finances/{id}', [FinanceController::class, 'showSingleFinance'])->name('admin.dashboard.show-finance');
        Route::get('/finances/{id}/edit', [FinanceController::class, 'editFinance'])->name('admin.dashboard.edit-finance');
        Route::patch('/finances/{finance}', [FinanceController::class, 'updateFinance'])->name('admin.dashboard.update-finances');
        Route::post('/finances/{finance}', [FinanceController::class, 'exportSingleFinance'])->name('admin.dashboard.export-finance');
    });


    // Users
    Route::prefix('/dashboard/{dashboardType}')->group(function () {
        Route::get('/users', [LinkedUserController::class, 'showUsers'])->name('admin.dashboard.users');
        Route::get('/users/get', [LinkedUserController::class, 'getUsers'])->name('admin.dashboard.get-users');
        Route::get('/users/new-user', [LinkedUserController::class, 'newUser'])->name('admin.dashboard.new-user');
        Route::get('/users/search-users', [LinkedUserController::class, 'searchUsers'])->name('admin.dashboard.search-users');
        Route::post('/users/add-user/{id}', [LinkedUserController::class, 'linkUser'])->name('admin.dashboard.link-user');
        Route::delete('/users/delete-user/{id}', [LinkedUserController::class, 'deleteUser'])->name('admin.dashboard.delete-user');
    });

    // Notes
    //TODO - Finish
    Route::prefix('/dashboard/{dashboardType}')->group(function () {
        Route::get('/notes', [NoteController::class, 'showNotes'])->name('admin.dashboard.show-notes');
        Route::get('/note-items', [NoteController::class, 'getNotes'])->name('admin.dashboard.note-items');
        Route::post('/notes/new', [NoteController::class, 'newNoteItem'])->name('admin.dashboard.new-note-item');
        Route::post('/note-item/{id}/complete', [NoteController::class, 'completeNoteItem'])->name('admin.dashboard.complete-note');
        Route::post('/note-item/{id}/uncomplete', [NoteController::class, 'uncompleteNoteItem'])->name('admin.dashboard.uncomplete-note-item');
        Route::delete('/note-item/{id}', [NoteController::class, 'deleteNoteItem'])->name('admin.dashboard.delete-note');
        Route::get('/note-item/completed-notes', [NoteController::class, 'showCompletedNoteItems'])->name('admin.dashboard.completed-notes');
    });

    // Reviews
    Route::prefix('/dashboard/{dashboardType}')->group(function () {
        Route::get('/reviews/{filter?}', [ReviewController::class, 'getPromoterReviews'])->name('admin.dashboard.get-reviews');
        Route::get('/filtered-reviews/{filter?}', [ReviewController::class, 'fetchReviews'])->name('admin.dashboard.fetch-reviews');
        Route::get('/reviews/pending', [ReviewController::class, 'showPendingReviews'])->name('admin.dashboard.show-pending-reviews');
        Route::get('/reviews/all', [ReviewController::class, 'showAllReviews'])->name('admin.dashboard.show-all-reviews');
        Route::post('/approve-display/{reviewId}', [ReviewController::class, 'approveDisplayReview'])->name('admin.dashboard.approve-display-review');
        Route::post('/hide-display-review/{reviewId}', [ReviewController::class, 'hideReview'])->name('admin.dashboard.hide-display-review');
        Route::post('/approve/{reviewId}', [ReviewController::class, 'approveReview'])->name('admin.dashboard.approve-pending-review');
        Route::post('/unapprove-review/{reviewId}', [ReviewController::class, 'unapproveReview'])->name('admin.dashboard.unapprove-review');
        Route::delete('/delete-review/{reviewId}', [ReviewController::class, 'deleteReview'])->name('admin.dashboard.delete-review');
    });

    // Documents
    Route::prefix('/dashboard/{dashboardType}')->group(function () {
        Route::get('/documents', [DocumentController::class, 'index'])->name('admin.dashboard.documents.index');
        Route::get('/documents/new', [DocumentController::class, 'create'])->name('admin.dashboard.document.create');
        Route::get('/documents/{id}', [DocumentController::class, 'show'])->name('admin.dashboard.document.show');
        Route::get('/documents/{id}/edit', [DocumentController::class, 'edit'])->name('admin.dashboard.document.edit');
        Route::post('/document/file-upload', [DocumentController::class, 'fileUpload'])->name('admin.dashboard.document.file.upload');
        Route::post('/documents/store', [DocumentController::class, 'storeDocument'])->name('admin.dashboard.store-document');
        Route::put('/documents/{id}', [DocumentController::class, 'update'])->name('admin.dashboard.document.update');
        Route::delete('/documents/{id}', [DocumentController::class, 'destroy'])->name('admin.dashboard.document.delete');
        Route::get('/documents/{id}/download', [DocumentController::class, 'download'])->name('admin.dashboard.document.download');
    });

    // Events
    // TODO - Finish
    Route::prefix('/dashboard/{dashboardType}')->group(function () {
        Route::get('/events', [EventController::class, 'showEvents'])->name('admin.dashboard.show-events');
        Route::get('/events/load-more-upcoming', [EventController::class, 'loadMoreUpcomingEvents'])->name('admin.dashboard.load-more-upcoming-events');
        Route::get('/events/load-more-past', [EventController::class, 'loadMorePastEvents'])->name('admin.dashboard.load-more-past-events');
        Route::get('/events/create-event', [EventController::class, 'createNewEvent'])->name('admin.dashboard.create-new-event');
        Route::post('/events/store-event', [EventController::class, 'storeNewEvent'])->name('admin.dashboard.store-new-event');
        Route::get('/events/search-venues', [EventController::class, 'selectVenue'])->name('admin.dashboard.search-venues');
        Route::get('/events/search-promoters', [EventController::class, 'selectPromoter'])->name('admin.dashboard.search-promoters');
        Route::get('/events/{id}', [EventController::class, 'showEvent'])->name('admin.dashboard.show-event');
        Route::get('/events/{id}/edit', [EventController::class, 'editEvent'])->name('admin.dashboard.edit-event');
        Route::put('/events/{id}/update', [EventController::class, 'updateEvent'])->name('admin.dashboard.update-event');
        Route::post('/events/{id}/add-to-calendar', [CalendarController::class, 'addEventToCalendar'])->name('admin.dashboard.add-event-to-calendar');
        Route::get('/events/{user}/check-linked-calendars', [CalendarController::class, 'checkLinkedCalendars']);
        Route::delete('/events/{id}/delete', [EventController::class, 'deleteEvent'])->name('admin.dashboard.delete-event');
    });

    // To-Do List
    Route::prefix('dashboard/{dashboardType}')->group(function () {
        Route::get('/todo-list', [TodoController::class, 'showTodos'])->name('admin.dashboard.todo-list');
        Route::get('/todo-items', [TodoController::class, 'getTodos'])->name('admin.dashboard.todo-items');
        Route::post('/todo-list/new', [TodoController::class, 'newTodoItem'])->name('admin.dashboard.new-todo-item');
        Route::post('/todo-item/{id}/complete', [TodoController::class, 'completeTodoItem'])->name('admin.dashboard.complete-todo-item');
        Route::post('/todo-item/{id}/uncomplete', [TodoController::class, 'uncompleteTodoItem'])->name('admin.dashboard.uncomplete-todo-item');
        Route::delete('/todo-item/{id}', [TodoController::class, 'deleteTodoItem'])->name('admin.dashboard.delete-todo-item');
        Route::get('/todo-item/completed-items', [TodoController::class, 'showCompletedTodoItems'])->name('admin.dashboard.completed-todo-items');
    });
});

Route::middleware(['auth'])->group(function () {});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile/events/{user}', [APIRequestsController::class, 'getUserCalendarEvents']);
    Route::get('profile/events/{user}/apple/sync', [CalendarController::class, 'syncAllEventsToAppleCalendar'])->name('apple.sync');
    Route::get('/profile/{dashboardType}/{id}', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/{dashboardType}/{user}', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/profile/{dashboardType}/settings/', [ProfileController::class, 'settings'])->name('settings.index');
    Route::post('/profile/{dashboardType}/settings/update', [ProfileController::class, 'updateModule'])->name('settings.updateModule');


    Route::get('auth/google', [CalendarController::class, 'redirectToGoogle'])->name('google.redirect');
    Route::get('auth/google/callback', [CalendarController::class, 'handleGoogleCallback']);
    Route::post('google/sync', [CalendarController::class, 'syncGoogleCalendar'])->name('google.sync');
    Route::post('google/unlink', [CalendarController::class, 'unlinkGoogle'])->name('google.unlink');

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
