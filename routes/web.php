<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;

// SHOP (PUBLIC)
Route::get('/', [ShopController::class, 'index'])->name('shop.index');
Route::get('/produk/{slug}', [ShopController::class, 'show'])->name('shop.show');

// CART
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

// CHECKOUT & ORDER
Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout.index');
Route::post('/checkout', [OrderController::class, 'store'])->name('checkout.store');
Route::get('/pesanan/{orderNumber}', [OrderController::class, 'show'])->name('order.show');
Route::get('/cek-pesanan', [OrderController::class, 'track'])->name('order.track');

// ADMIN AUTH
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::middleware('admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('products', ProductController::class);
        Route::post('/products/{product}/toggle', [ProductController::class, 'toggle'])->name('products.toggle');
        Route::resource('categories', CategoryController::class);
        Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
        Route::get('/orders/{order}/invoice', [AdminOrderController::class, 'invoice'])->name('orders.invoice');
        Route::post('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');
    });
});