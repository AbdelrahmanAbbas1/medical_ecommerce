<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use Illuminate\Support\Facades\Route;

/**
 * Web Routes for Medical Ecommerce System
 * 
 * This file defines all the web routes for the medical ecommerce application.
 * Routes are organized by functionality and include appropriate middleware
 * for authentication, authorization, and security.
 * 
 * Route Groups:
 * - Public routes: Home page, product browsing, cart operations
 * - Authenticated routes: Profile management, dashboard
 * - Admin routes: Product and order management (admin only)
 * - Guest routes: Authentication (login, register, password reset)
 * 
 * Security Features:
 * - Authentication middleware for protected routes
 * - Admin middleware for administrative functions
 * - Email verification for user accounts
 * - CSRF protection on all forms
 * 
 * @package Routes
 */

// Dashboard route - requires authentication and email verification
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Profile management routes - requires authentication
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Public home page - displays all products
Route::get('/', [ProductController::class, 'index'])->name('home');

// Shopping Cart Routes
// These routes handle cart operations and are accessible to all users
Route::post('/cart/add/{product}', [CartController::class,'add'])->name('cart.add');        // Add product to cart
Route::get('/cart', [CartController::class,'index'])->name('cart.index');                   // View cart contents
Route::post('/cart/update/{product}', [CartController::class,'update'])->name('cart.update'); // Update item quantity
Route::post('/cart/remove/{product}', [CartController::class,'remove'])->name('cart.remove'); // Remove item from cart

// Checkout Routes
// These routes handle the checkout process and order creation
Route::get('/checkout', [CheckoutController::class,'show'])->name('checkout.show');         // Display checkout form
Route::post('/checkout', [CheckoutController::class,'process'])->name('checkout.process');  // Process order
Route::get('/order/{order}/confirmation', [CheckoutController::class,'confirmation'])->name('order.confirmation'); // Order confirmation

// Admin Routes
// These routes are protected by authentication and admin middleware
// Only users with admin privileges can access these routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Product management routes (full CRUD operations)
    Route::resource('products', AdminProductController::class);
    
    // Order management routes (read-only for viewing orders)
    Route::resource('orders', OrderController::class)->only(['index', 'show']);
});

// Include authentication routes (login, register, password reset, etc.)
require __DIR__.'/auth.php';
