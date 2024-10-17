\<?php

    use App\Models\OtherService;
    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\AdminController;
    use App\Http\Controllers\VenueController;
    use App\Http\Controllers\ProfileController;
    use App\Http\Controllers\PromoterController;
    use App\Http\Controllers\DashboardController;
    use App\Http\Controllers\APIRequestsController;
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
        Route::post('/dashboard/notes/store-note', [DashboardController::class, 'storeNewNote'])->name('dashboard.store-new-note');

        Route::get('/dashboard/promoter', [PromoterDashboardController::class, 'index'])->name('promoter.dashboard');
        // Finances
        Route::get('/dashboard/promoter/finances', [PromoterDashboardController::class, 'promoterFinances'])->name('promoter.dashboard.finances');
        Route::get('/dashboard/promoter/finances/new-budget', [PromoterDashboardController::class, 'createNewPromoterBudget'])->name('promoter.dashboard.finances.new');
        Route::post('/dashboard/promoter/finances/save-budget', [PromoterDashboardController::class, 'saveNewPromoterBudget'])->name('promoter.dashboard.finances.saveNew');
        Route::post('/dashboard/promoter/finances/export', [PromoterDashboardController::class, 'exportFinances'])->name('promoter.dashboard.finances.export');
        Route::get('/dashboard/promoter/finances/{id}', [PromoterDashboardController::class, 'showSingleFinance'])->name('promoter.dashboard.finances.show');
        Route::get('/dashboard/promoter/finances/{id}/edit', [PromoterDashboardController::class, 'editSingleFinance'])->name('promoter.dashboard.finances.edit');
        Route::patch('/dashboard/promoter/finances/{finance}', [PromoterDashboardController::class, 'updateSingleFinance'])->name('promoter.dashboard.finances.update');
        Route::post('/dashboard/promoter/finances/{finance}', [PromoterDashboardController::class, 'exportSingleFinanceRecord'])->name('promoter.dashboard.finances.exportSingleFinanceRecord');
        // To Do List
        Route::get('/dashboard/promoter/todo-list', [PromoterDashboardController::class, 'showPromoterTodos'])->name('promoter.dashboard.todo-list');
        Route::get('/dashboard/promoter/todo-items', [PromoterDashboardController::class, 'getPromoterTodos'])->name('promoter.dashboard.todo-items');
        Route::post('/dashboard/promoter/todolist/new', [PromoterDashboardController::class, 'addNewTodoItem'])->name('promoter.dashboard.new-todo-item');
        Route::post('/dashboard/promoter/todo-item/{id}/complete', [PromoterDashboardController::class, 'completeTodoItem'])->name('promoter.dashboard.complete-todo-item');
        Route::delete('/dashboard/promoter/todo-item/{id}', [PromoterDashboardController::class, 'deleteTodoItem'])->name('promoter.dashboard.delete-todo-item');
        Route::get('/dashboard/promoter/todo-item/completed-items', [PromoterDashboardController::class, 'showCompletedTodoItems'])->name('promoter.dashboard.completed-todo-items');
        // Link User To Promoter
        Route::get('/dashboard/promoter/search', [PromoterDashboardController::class, 'searchExistingPromoters'])->name('admin.dashboard.promoter.search');
        Route::post('/dashboard/promoter/link', [PromoterDashboardController::class, 'linkToExistingPromoter'])->name('admin.dashboard.promoter.link');
        Route::post('/dashboard/promoter/store', [PromoterDashboardController::class, 'storeNewPromoter'])->name('admin.dashboard.promoter.store');
        // Users
        Route::get('/dashboard/promoter/users', [PromoterDashboardController::class, 'promoterUsers'])->name('promter.dashboard.users');
        Route::get('/dashboard/promoter/users/get', [PromoterDashboardController::class, 'getPromoterusers'])->name('admin.promoter.dashboard.get-users');
        Route::get('/dashboard/promoter/users/new-user', [PromoterDashboardController::class, 'newUser'])->name('promoter.dashboard.users.new');
        Route::get('/dashboard/promoter/users/search-users', [PromoterDashboardController::class, 'searchUsers'])->name('admin.dashboard.promoter.search-users');
        Route::post('/dashboard/promoter/users/add-user', [PromoterDashboardController::class, 'addUserToCompany'])->name('admin.dashboard.promoter.add-user-to-company');
        Route::delete('/dashboard/promoter/delete-user', [PromoterDashboardController::class, 'deleteUserFromCompany'])->name('admin.dashboard.promoter.delete-user');
        // Events
        Route::get('/dashboard/promoter/events', [PromoterDashboardController::class, 'showPromoterEvents'])->name('admin.dashboard.promoter.show-events');
        Route::get('/dashboard/promoter/events/load-more-upcoming', [PromoterDashboardController::class, 'loadMoreUpcomingEvents'])->name('admin.dashboard.promoter.load-more-upcoming-events');
        Route::get('/dashboard/promoter/events/load-more-past', [PromoterDashboardController::class, 'loadMorePastEvents'])->name('admin.dashboard.promoter.load-more-past-events');
        Route::get('/dashboard/promoter/events/create-event', [PromoterDashboardController::class, 'createNewPromoterEvent'])->name('admin.dashboard.promoter.create-new-event');
        Route::get('/dashboard/promoter/events/search-venues', [PromoterDashboardController::class, 'eventSelectVenue'])->name('admin.dashboard.promoter.search-venues');
        Route::post('/dashboard/promoter/events/store-event', [PromoterDashboardController::class, 'storeNewPromoterEvent'])->name('admin.dashboard.promoter.store-new-event');
        Route::get('/dashboard/promoter/events/{id}', [PromoterDashboardController::class, 'showSinglePromoterEvent'])->name('admin.dashboard.promoter.show-single-event');
        Route::get('dashboard/promoter/events/{id}/edit', [PromoterDashboardController::class, 'editSinglePromoterEvent'])->name('admin.dashboard.promoter.single-event.edit');
        Route::put('dashboard/promoter/events/{id}/update', [PromoterDashboardController::class, 'updateSinglePromoterEvent'])->name('admin.dashboard.promoter.single-event.update');

        Route::delete('/dashboard/promoter/events/{id}', [PromoterDashboardController::class, 'deleteSinglePromoterEvent'])->name('admin.dashboard.promoter.delete-single-event');
        //Notes
        Route::get('/dashboard/promoter/notes', [PromoterDashboardController::class, 'showPromoterNotes'])->name('admin.dashboard.promoter.show-notes');
        Route::get('/dashboard/promoter/note-items', [PromoterDashboardController::class, 'getPromoterNotes'])->name('admin.promoter.dashboard.note-items');
        Route::post('/dashboard/promoter/note-item/{id}/complete', [PromoterDashboardController::class, 'completeNoteItem'])->name('admin.promoter.dashboard.complete-note');
        Route::delete('/dashboard/promoter/note-item/{id}', [PromoterDashboardController::class, 'deleteNoteItem'])->name('admin.promoter.dashboard.delete-note');
        Route::get('/dashboard/promoter/note-item/completed-notes', [PromoterDashboardController::class, 'showCompletedNoteItems'])->name('admin.promoter.dashboard.completed-notes');
        // Reviews
        Route::get('/dashboard/promoter/reviews/{filter?}', [PromoterDashboardController::class, 'getPromoterReviews'])->name('admin.promoter.dashboard.get-reviews');
        Route::get('/dashboard/promoter/filtered-reviews/{filter?}', [PromoterDashboardController::class, 'fetchReviews'])->name('admin.promoter.dashboard.fetch-reviews');
        Route::get('/dashboard/promoter/reviews/pending', [PromoterDashboardController::class, 'showPendingPromoterReviews'])->name('admin.promoter.dashboard.show-pending-reviews');
        Route::get('/dashboard/promoter/reviews/all', [PromoterDashboardController::class, 'showAllPromoterReviews'])->name('admin.promoter.dashboard.show-all-reviews');
        Route::post('/dashboard/promoter/approve-display-promoter/{reviewId}', [PromoterDashboardController::class, 'approveDisplayPromoterReview'])->name('admin.promoter.dashboard.approve-display-review');
        Route::post('/promoter/dashboard/hide-display-review/{reviewId}', [PromoterDashboardController::class, 'hidePromoterReview'])->name('admin.promoter.dashboard.hide-display-review');
        Route::post('/dashboard/promoter/approve-promoter/{reviewId}', [PromoterDashboardController::class, 'approvePromoterReview'])->name('admin.promoter.dashboard.approve-pending-review');
        Route::post('/promoter/dashboard/unapprove-review/{reviewId}', [PromoterDashboardController::class, 'unapprovePromoterReview'])->name('admin.promoter.dashboard.unapprove-review');
        Route::delete('/dashboard/promoter/delete-review/{reviewId}', [PromoterDashboardController::class, 'deletePromoterReview'])->name('admin.promoter.dashboard.delete-review');
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
