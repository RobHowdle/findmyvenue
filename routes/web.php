<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VenueController;
use App\Http\Controllers\ProfileController;

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


// Route::group(function () {
    
//     Route::middleware('auth' {
//     Route::get('/venue/{id}', [VenueController::class, 'edit'])->name('venue.edit');
//     Route::patch('/venue/{id}', [VenueController::class, 'update'])->name('venue.update');
//     Route::delete('/venue/{id}', [VenueController::class, 'destroy'])->name('venue.destroy');
//     });
// });

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';