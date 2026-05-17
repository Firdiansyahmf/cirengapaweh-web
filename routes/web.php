<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

// >____________RUTE PELANGGAN (WEB UTAMA)
Route::get('/', function () {
    return view('pages.index');
});

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
    Route::delete('/produk/{product}', [ProductController::class, 'destroy'])->name('produk.destroy');

    Route::get('/lokasi', function () {
        return view('admin.lokasi');
    });

    Route::get('/promo', function () {
        return view('admin.promo');
    });

    Route::get('/pengguna', function () {
        return view('admin.pengguna');
    });
});
