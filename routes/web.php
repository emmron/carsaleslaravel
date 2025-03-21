<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CarListingController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

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

// Public routes
Route::get('/', [CarListingController::class, 'index'])->name('home');
Route::get('/listings', [CarListingController::class, 'index'])->name('listings.index');
Route::get('/listings/{listing}', [CarListingController::class, 'show'])->name('listings.show');

// Authentication routes
Route::middleware('auth')->group(function () {
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Car listing management routes
    Route::get('/my-listings', [CarListingController::class, 'myListings'])->name('listings.my');
    Route::get('/listings/create', [CarListingController::class, 'create'])->name('listings.create');
    Route::post('/listings', [CarListingController::class, 'store'])->name('listings.store');
    Route::get('/listings/{listing}/edit', [CarListingController::class, 'edit'])->name('listings.edit');
    Route::put('/listings/{listing}', [CarListingController::class, 'update'])->name('listings.update');
    Route::delete('/listings/{listing}', [CarListingController::class, 'destroy'])->name('listings.destroy');

    // Inquiry routes
    Route::post('/listings/{listing}/inquiries', [InquiryController::class, 'store'])->name('inquiries.store');
    Route::get('/inquiries', [InquiryController::class, 'index'])->name('inquiries.index');
    Route::get('/inquiries/{inquiry}', [InquiryController::class, 'show'])->name('inquiries.show');
    Route::post('/inquiries/{inquiry}/reply', [InquiryController::class, 'reply'])->name('inquiries.reply');
    Route::patch('/inquiries/{inquiry}/close', [InquiryController::class, 'close'])->name('inquiries.close');

    // Admin routes
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        // Users management
        Route::get('/users', [AdminController::class, 'users'])->name('users');

        // Listings management
        Route::get('/listings', [AdminController::class, 'listings'])->name('listings');
        Route::patch('/listings/{listing}/toggle-featured', [AdminController::class, 'toggleFeatured'])->name('listings.toggle-featured');

        // Features management
        Route::get('/features', [AdminController::class, 'features'])->name('features');
        Route::post('/features', [AdminController::class, 'storeFeature'])->name('features.store');
        Route::put('/features/{feature}', [AdminController::class, 'updateFeature'])->name('features.update');
        Route::delete('/features/{feature}', [AdminController::class, 'destroyFeature'])->name('features.destroy');
    });
});

require __DIR__.'/auth.php';