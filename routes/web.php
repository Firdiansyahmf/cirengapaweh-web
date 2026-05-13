<?php

use Illuminate\Support\Facades\Route;

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

    Route::get('/produk', function () {
        return view('admin.produk');
    });

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
