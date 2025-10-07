<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\WishlistController;

// Auth routes (signup, login, 2FA)
Route::post('/signup', [AuthController::class, 'signup']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/2fa-verify', [AuthController::class, 'verify2fa']);

// Protected routes requiring Sanctum auth
Route::middleware('auth:sanctum')->group(function () {

    // Logout
    Route::post('/logout', [AuthController::class, 'logout']);

    // Cart APIs
    Route::get('/cart', [CartController::class, 'show']); // Get all cart items
    Route::post('/cart/add', [CartController::class, 'add']); // Add item to cart
    Route::post('/cart/update', [CartController::class, 'updateQuantity']); // Update quantity
    Route::post('/cart/remove', [CartController::class, 'remove']); // Remove item
    Route::delete('/cart/clear', [CartController::class, 'clear']); // Clear cart

    // Wishlist APIs
    Route::get('/wishlist', [WishlistController::class, 'index']); // Get wishlist
    Route::post('/wishlist/{product}', [WishlistController::class, 'store']); // Add to wishlist
    Route::delete('/wishlist/{id}', [WishlistController::class, 'destroy']); // Remove from wishlist
});
