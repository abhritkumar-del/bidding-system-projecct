<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\AuctionController;
use App\Http\Controllers\ProductController;

// Admin Controllers
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\AuctionController as AdminAuctionController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;

// Seller Controllers
use App\Http\Controllers\Seller\SellerController;
use App\Http\Controllers\Seller\ProductController as SellerProductController;
use App\Http\Controllers\Seller\AuctionController as SellerAuctionController;

// Bidder Controllers
use App\Http\Controllers\Bidder\BidderController;
use App\Http\Controllers\Bidder\BidController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Homepage
Route::get('/', [HomeController::class, 'index'])->name('home');

// Public Auctions
Route::get('/auctions', [AuctionController::class, 'index'])->name('auctions.index');
Route::get('/auctions/{auction}', [AuctionController::class, 'show'])->name('auctions.show');

// Public Products
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

Auth::routes(['verify' => false]);

// Custom Registration with Role Selection
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    
    // Dashboard - Redirects based on role
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Admin Routes - Protected by AdminMiddleware
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
        
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        
        // User Management
        Route::get('/users', [AdminController::class, 'users'])->name('users.index');
        Route::post('/users/{id}/approve', [AdminController::class, 'approveUser'])->name('users.approve');
        Route::post('/users/{id}/ban', [AdminController::class, 'banUser'])->name('users.ban');
        Route::post('/users/{id}/unban', [AdminController::class, 'unbanUser'])->name('users.unban');
        
        // Category Management
        Route::resource('categories', AdminCategoryController::class);
        Route::post('/categories/{category}/toggle', [AdminCategoryController::class, 'toggleStatus'])->name('categories.toggle');
        
        // Product Management
        Route::get('/products', [AdminProductController::class, 'index'])->name('products.index');
        Route::get('/products/{product}', [AdminProductController::class, 'show'])->name('products.show');
        Route::post('/products/{product}/approve', [AdminProductController::class, 'approve'])->name('products.approve');
        Route::post('/products/{product}/reject', [AdminProductController::class, 'reject'])->name('products.reject');
        Route::delete('/products/{product}', [AdminProductController::class, 'destroy'])->name('products.destroy');
        
        // Auction Management
        Route::get('/auctions', [AdminAuctionController::class, 'index'])->name('auctions.index');
        Route::get('/auctions/{auction}', [AdminAuctionController::class, 'show'])->name('auctions.show');
        Route::post('/auctions/{auction}/cancel', [AdminAuctionController::class, 'cancel'])->name('auctions.cancel');
        
        // Payment Management
        Route::get('/payments', [AdminPaymentController::class, 'index'])->name('payments.index');
        Route::post('/payments/{payment}/approve', [AdminPaymentController::class, 'approve'])->name('payments.approve');
        Route::post('/payments/{payment}/reject', [AdminPaymentController::class, 'reject'])->name('payments.reject');
    });

    /*
    |--------------------------------------------------------------------------
    | Seller Routes - Protected by SellerMiddleware
    |--------------------------------------------------------------------------
    */
    Route::prefix('seller')->name('seller.')->middleware('seller')->group(function () {
        
        Route::get('/dashboard', [SellerController::class, 'dashboard'])->name('dashboard');
        
        // Product Management
        Route::resource('products', SellerProductController::class);
        
        // Auction Management
        Route::get('/auctions', [SellerAuctionController::class, 'index'])->name('auctions.index');
        Route::get('/auctions/create', [SellerAuctionController::class, 'create'])->name('auctions.create');
        Route::post('/auctions', [SellerAuctionController::class, 'store'])->name('auctions.store');
        Route::get('/auctions/{auction}', [SellerAuctionController::class, 'show'])->name('auctions.show');
    });

    /*
    |--------------------------------------------------------------------------
    | Bidder Routes - Protected by BidderMiddleware
    |--------------------------------------------------------------------------
    */
    Route::prefix('bidder')->name('bidder.')->middleware('bidder')->group(function () {
        
        Route::get('/dashboard', [BidderController::class, 'dashboard'])->name('dashboard');
        
        // Bidding
        Route::get('/bids', [BidderController::class, 'myBids'])->name('bids.index');
        Route::get('/auctions/{auction}/bid', [BidController::class, 'create'])->name('bids.create');
        Route::post('/auctions/{auction}/bid', [BidController::class, 'store'])->name('bids.store');
        
        // Won Auctions
        Route::get('/won-auctions', [BidderController::class, 'wonAuctions'])->name('won-auctions');

        // Payment Management
        Route::get('/payments', [\App\Http\Controllers\Bidder\PaymentController::class, 'index'])->name('payments.index');
        Route::get('/auctions/{auction}/payment', [\App\Http\Controllers\Bidder\PaymentController::class, 'create'])->name('payments.create');
        Route::post('/auctions/{auction}/payment', [\App\Http\Controllers\Bidder\PaymentController::class, 'store'])->name('payments.store');
        Route::get('/payments/{payment}', [\App\Http\Controllers\Bidder\PaymentController::class, 'show'])->name('payments.show');
    });
});