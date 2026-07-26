<?php

use Illuminate\Support\Facades\Route;
use App\Models\Product;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\AuthController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrderController;

// Tambahkan ini di web.php
Route::post('/midtrans/notification', [OrderController::class, 'notification']);
// ==========================================
// PENGUNJUNG UMUM & KATALOG
// ==========================================
Route::get('/', function () {
    $products = Product::with('category')
        ->where('is_active', true)
        ->latest()
        ->take(4)
        ->get();
    
    return view('home', compact('products'));
});

// Route Product Detail (Hanya satu route yang dipakai)
Route::get('/product/{slug}', function ($slug) {
    // 1. Cari produk utama
    $product = Product::with(['category', 'variants'])
        ->where('slug', $slug)
        ->where('is_active', true)
        ->firstOrFail();

    // 2. Ambil 4 produk rekomendasi
    $relatedProducts = Product::with('category')
        ->where('category_id', $product->category_id)
        ->where('id', '!=', $product->id)
        ->where('is_active', true)
        ->latest()
        ->take(4)
        ->get();

    return view('product-detail', compact('product', 'relatedProducts'));
});

// Route Navbar Kategori
Route::get('/new-arrivals', [ShopController::class, 'newArrivals'])->name('shop.new-arrivals');
Route::get('/best-sellers', [ShopController::class, 'bestSellers'])->name('shop.best-sellers');
Route::get('/collections', [ShopController::class, 'collections'])->name('shop.collections');

// ==========================================
// SISTEM OTENTIKASI (LOGIN & REGISTER)
// ==========================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');


// ==========================================
// VERIFIKASI EMAIL
// ==========================================
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill(); 
    return redirect('/')->with('success', 'Email berhasil diverifikasi! Selamat berbelanja.');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Link verifikasi yang baru telah dikirim ke email Anda!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');


// ==========================================
// KERANJANG & CHECKOUT
// ==========================================
Route::post('/cart/add', [CheckoutController::class, 'addToCart'])->name('cart.add');
Route::post('/cart/update/{cartKey}', [App\Http\Controllers\CheckoutController::class, 'updateCart'])->name('cart.update');
Route::post('/cart/remove/{cartKey}', [App\Http\Controllers\CheckoutController::class, 'removeItem'])->name('cart.remove');
// Dilindungi middleware auth (harus login) dan verified (harus sudah verifikasi email)
Route::get('/checkout', [CheckoutController::class, 'checkout'])->name('checkout')->middleware(['auth', 'verified']);
Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process')->middleware(['auth', 'verified']);
Route::get('/pesanan/{id}/bayar', [CheckoutController::class, 'pay'])->name('order.pay');

// ==========================================
// PROFIL CUSTOMER
// ==========================================
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    
    // Rute Baru untuk Alamat
    Route::post('/profile/address', [ProfileController::class, 'storeAddress'])->name('profile.address.store');
    Route::put('/profile/address/{id}', [ProfileController::class, 'updateAddress'])->name('profile.address.update');
    Route::delete('/profile/address/{id}', [ProfileController::class, 'destroyAddress'])->name('profile.address.destroy');
});