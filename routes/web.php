<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/signup', [AuthController::class, 'showSignup'])->name('signup');
    Route::post('/signup', [AuthController::class, 'signup']);

    Route::get('/2fa', [AuthController::class, 'show2faForm'])->name('2fa.form');
    Route::post('/2fa', [AuthController::class, 'verify2fa'])->name('2fa.verify');

    Route::get('/email/verify', [AuthController::class, 'showEmailVerifyForm'])->name('email.verify');
    Route::post('/email/verify', [AuthController::class, 'sendEmailVerificationOtp'])->name('email.verify.send');
    Route::post('/email/verify/confirm', [AuthController::class, 'confirmEmailVerificationOtp'])->name('email.verify.confirm');

    Route::get('/category/{id}/products', [ProductController::class, 'productsByCategory'])->name('category.products');
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
    Route::get('/cart', [CartController::class, 'show'])->name('cart.show');
    Route::get('product/{id}', [ProductController::class, 'show'])->name('product.show');


});




/*
|--------------------------------------------------------------------------
| Authenticated User Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::get('/cart', [CartController::class, 'show'])->name('cart.show');

    Route::post('/cart/update', [CartController::class, 'updateQuantity'])->name('cart.update');
    Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
    Route::get('/coupons/active', [CartController::class, 'activeCoupons']);

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout/address/add', [CheckoutController::class, 'addAddress'])->name('checkout.address.add');
    Route::post('/checkout/address/{address}/edit', [CheckoutController::class, 'editAddress'])->name('checkout.address.edit');

    Route::post('/checkout/place-order', [OrderController::class, 'placeOrder'])->name('checkout.placeOrder');
    // Handle GET requests to the place-order route
    Route::get('/checkout/place-order', function () {
        // Redirect the user back to the cart page
        return redirect()->route('cart.show')->with('info', 'Please submit your order from the checkout page.');
    });

    Route::post('/checkout/orderSuccess/{orderId}', [OrderController::class, 'orderSuccess'])->name('checkout.orderSuccess');


    // Order success page
    Route::get('/order/success/{orderId}', [OrderController::class, 'orderSuccess'])->name('orders.success');

    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/{product}', [WishlistController::class, 'store'])->name('wishlist.store');
    Route::delete('/wishlist/{id}', [WishlistController::class, 'destroy'])->name('wishlist.destroy');

     Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    Route::post('/profile/email/send-otp', [ProfileController::class, 'sendOtp'])->name('profile.send_otp');
    Route::get('/profile/email/verify', [ProfileController::class, 'showOtpForm'])->name('otp.form');
    Route::post('/profile/email/verify', [ProfileController::class, 'verifyOtp'])->name('otp.verify');


    // // Optional: Track order
    // Route::get('/order/track/{orderId}', [OrderController::class, 'trackOrder'])->name('orders.track');

});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

// Admin login
Route::get('/admin/login', [AdminController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'login']);
Route::get('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout');

// Protected admin dashboard and resources
Route::middleware('auth:admin')->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // Product management
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{id}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');

    // Category management
    Route::resource('categories', CategoryController::class);
});
