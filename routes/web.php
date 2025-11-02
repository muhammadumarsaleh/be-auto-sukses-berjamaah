<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Mitra\ShopController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Mitra\MitraDashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 🟢 AUTH
Auth::routes();

// 🟢 COMPANY PROFILE (Public Pages)
Route::get('/', [HomeController::class, 'root'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::get('/services', [HomeController::class, 'services'])->name('services');

// 🟡 ADMIN AREA (sementara tanpa middleware)
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // CRUD Produk
    Route::delete('/products/image/{id}', [ProductController::class, 'destroyImage'])->name('products.image.destroy');
    Route::post('/products/{product}/add-image', [ProductController::class, 'addImage'])->name('products.add-image');
    Route::resource('products', ProductController::class);

    // CRUD User / Mitra
    Route::resource('users', UserController::class);

    // Manajemen Mitra & Transaksi
    Route::get('/mitra', [UserController::class, 'mitra'])->name('mitra.index');
    Route::get('/transactions', [AdminDashboardController::class, 'transactions'])->name('transactions');
});

// 🔵 MITRA AREA (sementara tanpa middleware)
Route::prefix('mitra')->name('mitra.')->group(function () {
    Route::get('/dashboard', [MitraDashboardController::class, 'index'])->name('dashboard');

    // SHOP (Produk untuk mitra)
    Route::prefix('shop')->name('shop.')->group(function () {
        Route::get('/', [ShopController::class, 'index'])->name('products');
        Route::get('/product/{id}', [ShopController::class, 'show'])->name('product.show');
        Route::post('/cart/add/{id}', [ShopController::class, 'addToCart'])->name('cart.add');
    });

    // CART & CHECKOUT
    Route::get('/cart', [ShopController::class, 'cart'])->name('cart');
    Route::get('/checkout', [ShopController::class, 'checkout'])->name('checkout');
    Route::post('/checkout/process', [ShopController::class, 'processCheckout'])->name('checkout.process');
    Route::get('/orders', [ShopController::class, 'orders'])->name('orders');
});

// 🟣 LANGUAGE
Route::get('index/{locale}', [HomeController::class, 'lang']);

// 🟣 PROFILE UPDATE
Route::post('/update-profile/{id}', [HomeController::class, 'updateProfile'])->name('updateProfile');
Route::post('/update-password/{id}', [HomeController::class, 'updatePassword'])->name('updatePassword');
