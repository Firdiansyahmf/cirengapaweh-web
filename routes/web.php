<?php

use App\Http\Middleware\AdminAuth;
use App\Models\PartnerLocation;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PromoController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\DashboardController;
// produk
use App\Models\Product;
// promo
use App\Models\Promo;
// detail produk
use Illuminate\Http\Request;


// >____________RUTE PELANGGAN (WEB UTAMA)
// START : RUTE UTAMA
// index
Route::get('/', function () {
    // START : RUTE SPESIFIK
    // produk
    $products = Product::query()
        ->where('is_active', true)
        ->orderBy('created_at', 'desc')
        ->get();
    // promo
    $promos = Promo::query()
        ->with('products')
        ->where('is_active', true)
        ->whereDate('start_date', '<=', now()->toDateString())
        ->whereDate('end_date', '>=', now()->toDateString())
        ->whereRaw('used_count < max_usage')
        ->orderBy('created_at', 'desc')
        ->get();
    // mitra
    $locations = PartnerLocation::query()
        ->where('is_active', true)
        ->orderBy('created_at', 'desc')
        ->get();
    // END : RUTE SPESIFIK

    return view('pages.index', compact('products', 'promos', 'locations'));
});

// tentang kami
Route::get("/tentang-kami", function () {
    return view("pages.tentangKami");
});

// detail produk
Route::get('/produk', function (Request $request) {
    $id = $request->query('id');
    if (!$id) {
        return redirect('/'); // ke 404 -> halaman tidak ditemukan
    }

    $product = \App\Models\Product::findOrFail($id);
    $activePromo = \App\Models\Promo::whereHas('products', function ($query) use ($id) {
        $query->where('products.id', $id);
    })
        ->where('is_active', true)
        ->whereDate('start_date', '<=', now()->toDateString())
        ->whereDate('end_date', '>=', now()->toDateString())
        ->whereRaw('used_count < max_usage')
        ->orderBy('created_at', 'desc')
        ->first();
        
    $finalPrice = $product->price;
    if ($activePromo) {
        $discountAmount = ($product->price * $activePromo->discount_percentage) / 100;
        $finalPrice = $product->price - $discountAmount;
    }

    return view('pages.produk', compact('product', 'activePromo', 'finalPrice'));
});

//checkout
Route::get('/checkout', [CheckoutController::class, 'show']);
Route::post('/checkout', [CheckoutController::class, 'prepare']);

// payment
Route::post('/payment', [CheckoutController::class, 'store'])->name('checkout.process');
Route::get('/payment', function () {
    $midtransResponse = session('midtrans_response');
    $invoiceNumber = session('active_invoice');
    
    // mengeluarkan user jika tidak punya memiliki session checkout
    if (!$midtransResponse || !$invoiceNumber) {
        return redirect('/'); 
    }

    return view('pages.payment', compact('invoiceNumber', 'midtransResponse'));
})->name('payment.show');

// midtrans webhook
Route::post('/payment/webhook', [\App\Http\Controllers\PaymentController::class, 'handleWebhook']);

/* temp route buat preview halaman pembayaran berhasil */
Route::get('/preview-paymentsuccess', function () {
    return view('pages.paymentSuccess');
});
// END : RUTE UTAMA


// >____________RUTE ADMIN DASHBOARD (CMS)
// semua url diawali /admin
Route::prefix('admin')->group(function () {

    // admin routes
    Route::middleware(AdminAuth::class)->group(function () {
        // login
        Route::get('/login', [LoginController::class, 'showLogin'])->name('admin.login.form');
        Route::post('/login', [LoginController::class, 'authenticate'])->name('admin.login');

        // Logout
        Route::post('/logout', [LoginController::class, 'logout'])->name('admin.logout');

        // Dashboard dan CRUD
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

        // Produk Routes
        Route::get('/produk', [ProductController::class, 'index'])->name('produk.index');
        Route::post('/produk', [ProductController::class, 'store'])->name('produk.store');
        Route::put('/produk/{product}', [ProductController::class, 'update'])->name('produk.update');
        Route::patch('/produk/{product}', [ProductController::class, 'updateStatus'])->name('produk.updateStatus');
        Route::delete('/produk/{product}', [ProductController::class, 'destroy'])->name('produk.destroy');

        // Promo Routes
        Route::get('/promo', [PromoController::class, 'index'])->name('promo.index');
        Route::get('/promo/get-products', [PromoController::class, 'getProducts'])->name('promo.getProducts');
        Route::get('/promo/{promo}', [PromoController::class, 'show'])->name('promo.show');
        Route::post('/promo', [PromoController::class, 'store'])->name('promo.store');
        Route::get('/promo/{promo}/edit', [PromoController::class, 'edit'])->name('promo.edit');
        Route::put('/promo/{promo}', [PromoController::class, 'update'])->name('promo.update');
        Route::patch('/promo/{promo}', [PromoController::class, 'updateStatus'])->name('promo.updateStatus');
        Route::delete('/promo/{promo}', [PromoController::class, 'destroy'])->name('promo.destroy');

        // Lokasi Routes
        Route::get('/lokasi', [LocationController::class, 'index'])->name('lokasi.index');
        Route::get('/lokasi/{location}', [LocationController::class, 'show'])->name('lokasi.show');
        Route::post('/lokasi', [LocationController::class, 'store'])->name('lokasi.store');
        Route::put('/lokasi/{location}', [LocationController::class, 'update'])->name('lokasi.update');
        Route::delete('/lokasi/{location}', [LocationController::class, 'destroy'])->name('lokasi.destroy');

        // Pemesanan Routes
        Route::get('/pemesanan', function () {
            return view('admin.pemesanan');
        });

        // Dashboard dan CRUD
        Route::get('/dashboard', [DashboardController::class, 'index']);

        // Produk Routes
        Route::get('/produk', [ProductController::class, 'index'])->name('produk.index');
        Route::post('/produk', [ProductController::class, 'store'])->name('produk.store');
        Route::put('/produk/{product}', [ProductController::class, 'update'])->name('produk.update');
        Route::patch('/produk/{product}', [ProductController::class, 'updateStatus'])->name('produk.updateStatus');
        Route::delete('/produk/{product}', [ProductController::class, 'destroy'])->name('produk.destroy');

        // Promo Routes
        Route::get('/promo', [PromoController::class, 'index'])->name('promo.index');
        Route::get('/promo/get-products', [PromoController::class, 'getProducts'])->name('promo.getProducts');
        Route::get('/promo/{promo}', [PromoController::class, 'show'])->name('promo.show');
        Route::post('/promo', [PromoController::class, 'store'])->name('promo.store');
        Route::get('/promo/{promo}/edit', [PromoController::class, 'edit'])->name('promo.edit');
        Route::put('/promo/{promo}', [PromoController::class, 'update'])->name('promo.update');
        Route::patch('/promo/{promo}', [PromoController::class, 'updateStatus'])->name('promo.updateStatus');
        Route::delete('/promo/{promo}', [PromoController::class, 'destroy'])->name('promo.destroy');

        // Lokasi Routes
        Route::get('/lokasi', [LocationController::class, 'index'])->name('lokasi.index');
        Route::get('/lokasi/{location}', [LocationController::class, 'show'])->name('lokasi.show');
        Route::post('/lokasi', [LocationController::class, 'store'])->name('lokasi.store');
        Route::put('/lokasi/{location}', [LocationController::class, 'update'])->name('lokasi.update');
        Route::delete('/lokasi/{location}', [LocationController::class, 'destroy'])->name('lokasi.destroy');

        Route::get('/pemesanan', function () {
            return view('admin.pemesanan');
        });

        // User Management Routes
        Route::get('/pengguna', [UserController::class, 'index'])->name('pengguna.index');
        Route::post('/pengguna', [UserController::class, 'store'])->name('pengguna.store');
        Route::put('/pengguna/{user}', [UserController::class, 'update'])->name('pengguna.update');
        Route::post('/pengguna/{user}/verify-password', [UserController::class, 'verifyPassword'])->name('pengguna.verifyPassword');
        Route::delete('/pengguna/{user}', [UserController::class, 'destroy'])->name('pengguna.destroy');
        Route::post('/pengguna/verify-password/{userId}', [UserController::class, 'verifyPassword']);

        // Chat Management Routes
        Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
        Route::get('/chat/{session}', [ChatController::class, 'show'])->name('chat.show');
        Route::post('/chat/{session}/send', [ChatController::class, 'sendMessage'])->name('chat.send');
        Route::post('/chat/{session}/close', [ChatController::class, 'closeSession'])->name('chat.close');
        Route::post('/chat/{session}/reopen', [ChatController::class, 'reopenSession'])->name('chat.reopen');
    });
});
