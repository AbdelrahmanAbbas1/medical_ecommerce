<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use Illuminate\Support\Facades\Route;


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::get('/', [ProductController::class, 'index'])->name('home');

// Cart Routes
Route::post('/cart/add/{product}', [CartController::class,'add'])->name('cart.add');
Route::get('/cart', [CartController::class,'index'])->name('cart.index');
Route::post('/cart/update/{product}', [CartController::class,'update'])->name('cart.update');
Route::post('/cart/remove/{product}', [CartController::class,'remove'])->name('cart.remove');


// Checkout Routes
Route::get('/checkout', [CheckoutController::class,'show'])->name('checkout.show');
Route::post('/checkout', [CheckoutController::class,'process'])->name('checkout.process');
Route::get('/order/{order}/confirmation', [CheckoutController::class,'confirmation'])->name('order.confirmation');


// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('products', AdminProductController::class);
    Route::resource('orders', OrderController::class)->only(['index', 'show']);
});

require __DIR__.'/auth.php';
