<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\CheckoutController;

// >____________RUTE PELANGGAN (WEB UTAMA)
Route::get('/', function () {
    return view('pages.index');
});

Route::get('/detail-produk', function () {
    return view('pages.produk');
});

// Minimal checkout routes (show accepts GET queries; store accepts POST from detail page)
Route::get('/checkout', [CheckoutController::class, 'show']);
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

// buat halaman tentang-kami jika sudah dipecah:
// Route::get("/tentang-kami", function () {
//     return view("pages.tentang-kami");
// });

// >____________RUTE ADMIN DASHBOARD (CMS)
// semua url diawali /admin
Route::prefix('admin')->group(function () {
    // Login
    Route::get('/loginAja', function () {
        return view('admin.login');
    });

    // Dashboard dan CRUD
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    });

    // Produk Routes
    Route::get('/produk', [ProductController::class, 'index'])->name('produk.index');
    Route::post('/produk', [ProductController::class, 'store'])->name('produk.store');
    Route::put('/produk/{product}', [ProductController::class, 'update'])->name('produk.update');
    Route::patch('/produk/{product}', [ProductController::class, 'updateStatus'])->name('produk.updateStatus');
    Route::delete('/produk/{product}', [ProductController::class, 'destroy'])->name('produk.destroy');

    // Lokasi Routes
    Route::get('/lokasi', [LocationController::class, 'index'])->name('lokasi.index');
    Route::get('/lokasi/{location}', [LocationController::class, 'show'])->name('lokasi.show');
    Route::post('/lokasi', [LocationController::class, 'store'])->name('lokasi.store');
    Route::put('/lokasi/{location}', [LocationController::class, 'update'])->name('lokasi.update');
    Route::delete('/lokasi/{location}', [LocationController::class, 'destroy'])->name('lokasi.destroy');

    Route::get('/promo', function () {
        return view('admin.promo');
    });

    Route::get('/pemesanan', function () {
        return view('admin.pemesanan');
    });

    Route::get('/pengguna', function () {
        return view('admin.pengguna');
    });
});
