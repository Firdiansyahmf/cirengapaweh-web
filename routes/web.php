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
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PengirimanController;
use App\Http\Controllers\PemesananController;
use App\Http\Controllers\PaymentController;

use App\Models\Product;
use App\Models\Promo;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\ChatSession;
use App\Models\ChatMessage;


// >____________RUTE KUSTOMER (WEB UTAMA)
// index
Route::get('/', function () {
    $products = Product::query()
        ->where('is_active', true)
        ->orderBy('created_at', 'desc')
        ->get();
    $promos = Promo::query()
        ->with('products')
        ->where('is_active', true)
        ->whereDate('start_date', '<=', now()->toDateString())
        ->whereDate('end_date', '>=', now()->toDateString())
        ->whereRaw('used_count < max_usage')
        ->orderBy('created_at', 'desc')
        ->get();
    $locations = PartnerLocation::query()
        ->where('is_active', true)
        ->orderBy('created_at', 'desc')
        ->get();
    return view('pages.index', compact('products', 'promos', 'locations'));
});

// tentang kami
Route::get("/tentang-kami", function () {
    $locations = PartnerLocation::query()
        ->where('is_active', true)
        ->orderBy('created_at', 'desc')
        ->get();
    return view("pages.tentangKami", compact('locations'));
});

// cari produk
Route::get('/api/search-products', function (Request $request) {
    $keyword = $request->keyword;
    $products = Product::query()
        ->where('name', 'like', "%{$keyword}%")
        ->limit(5)
        ->get();
    return response()->json($products);
});

// detail produk
Route::get('/produk', function (Request $request) {
    $id = $request->query('id');
    $promoId = $request->query('promo_id');
    
    if (!$id) {
        return redirect('/');
    }

    $product = Product::findOrFail($id);
    $activePromo = null;
    if ($promoId) {
        $activePromo = Promo::where('id', $promoId)
            ->where('is_active', true)
            ->whereDate('start_date', '<=', now()->toDateString())
            ->whereDate('end_date', '>=', now()->toDateString())
            ->whereRaw('used_count < max_usage')
            ->first();
    }

    if (!$activePromo) {
        $activePromo = Promo::whereHas('products', function ($query) use ($id) {
            $query->where('products.id', $id);
        })
            ->where('is_active', true)
            ->whereDate('start_date', '<=', now()->toDateString())
            ->whereDate('end_date', '>=', now()->toDateString())
            ->whereRaw('used_count < max_usage')
            ->orderBy('created_at', 'desc')
            ->first();
    }
    
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
Route::post('/payment/webhook', [PaymentController::class, 'handleWebhook']);

/* temp route buat preview halaman pembayaran berhasil */
Route::get('/preview-paymentsuccess', function () {
    return view('pages.paymentSuccess');
});

Route::post('/payment/success', function (Request $request) {
    $invoiceNumber = $request->input('invoice_number');
    $order = Order::where('invoice_number', $invoiceNumber)->firstOrFail();
    $payment = $order->payment;
    $orderItem = $order->items()->first();

    return view('pages.paymentSuccess', compact('order', 'payment', 'orderItem'));
});

// cek order
Route::get('/cek-order', function () {
    return view("pages.checkOrder");
});

// Order routes
Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');

// Tracking API
Route::get('/api/tracking/{delivery}', [PengirimanController::class, 'showTracking'])->name('api.tracking.show');

// Shipment webhook
Route::post('/webhooks/biteship', [PengirimanController::class, 'handleWebhook'])->name('biteship.webhook');

// END : RUTE UTAMA

// >____________RUTE ADMIN DASHBOARD (CMS)
Route::prefix('admin')->group(function () {
    // route wajib login admin (middleware)
    Route::middleware(AdminAuth::class)->group(function () {
        // login & logout
        Route::get('/login', [LoginController::class, 'showLogin'])->name('admin.login.form');
        Route::post('/login', [LoginController::class, 'authenticate'])->name('admin.login');
        Route::post('/logout', [LoginController::class, 'logout'])->name('admin.logout');
        // dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
        // produk
        Route::get('/produk', [ProductController::class, 'index'])->name('produk.index');
        Route::post('/produk', [ProductController::class, 'store'])->name('produk.store');
        Route::put('/produk/{product}', [ProductController::class, 'update'])->name('produk.update');
        Route::patch('/produk/{product}', [ProductController::class, 'updateStatus'])->name('produk.updateStatus');
        Route::delete('/produk/{product}', [ProductController::class, 'destroy'])->name('produk.destroy');
        // promo
        Route::get('/promo', [PromoController::class, 'index'])->name('promo.index');
        Route::get('/promo/get-products', [PromoController::class, 'getProducts'])->name('promo.getProducts');
        Route::get('/promo/{promo}', [PromoController::class, 'show'])->name('promo.show');
        Route::post('/promo', [PromoController::class, 'store'])->name('promo.store');
        Route::get('/promo/{promo}/edit', [PromoController::class, 'edit'])->name('promo.edit');
        Route::put('/promo/{promo}', [PromoController::class, 'update'])->name('promo.update');
        Route::patch('/promo/{promo}', [PromoController::class, 'updateStatus'])->name('promo.updateStatus');
        Route::delete('/promo/{promo}', [PromoController::class, 'destroy'])->name('promo.destroy');
        // lokasi
        Route::get('/lokasi', [LocationController::class, 'index'])->name('lokasi.index');
        Route::get('/lokasi/{location}', [LocationController::class, 'show'])->name('lokasi.show');
        Route::post('/lokasi', [LocationController::class, 'store'])->name('lokasi.store');
        Route::put('/lokasi/{location}', [LocationController::class, 'update'])->name('lokasi.update');
        Route::delete('/lokasi/{location}', [LocationController::class, 'destroy'])->name('lokasi.destroy');

        // Pemesanan Routes
        Route::get('/pemesanan', [PemesananController::class, 'index'])->name('pemesanan.index');
        Route::post('/pemesanan/{order}/process', [PemesananController::class, 'processShipment'])->name('pemesanan.processShipment');
        Route::post('/pemesanan/{order}/accept', [PemesananController::class, 'acceptOrder'])->name('pemesanan.accept');
        Route::post('/pemesanan/{order}/cancel', [PemesananController::class, 'cancelOrder'])->name('pemesanan.cancel');
        Route::get('/pemesanan/status/{status}', [OrderController::class, 'getByStatus'])->name('pemesanan.status');
        Route::put('/pemesanan/{order}/status', [OrderController::class, 'updateStatus'])->name('pemesanan.updateStatus');

        // User Management Routes

        Route::get('/pengguna', [UserController::class, 'index'])->name('pengguna.index');
        Route::post('/pengguna', [UserController::class, 'store'])->name('pengguna.store');
        Route::put('/pengguna/{user}', [UserController::class, 'update'])->name('pengguna.update');
        Route::post('/pengguna/{user}/verify-password', [UserController::class, 'verifyPassword'])->name('pengguna.verifyPassword');
        Route::delete('/pengguna/{user}', [UserController::class, 'destroy'])->name('pengguna.destroy');
        Route::post('/pengguna/verify-password/{userId}', [UserController::class, 'verifyPassword']);
        // chat admin
        Route::get('/chat', function () {
            return view('admin.chat');
        });
        // api sinkronisasi live chat
        Route::get('/chat-sync/sessions', function () {
            $sessions = ChatSession::query()->orderBy('updated_at', 'desc')->get();
            return response()->json($sessions);
        });
        Route::get('/chat-sync/{id}/messages', function ($id) {
            $messages = ChatMessage::query()
                ->where('session_id', $id)
                ->orderBy('created_at', 'asc')
                ->get();
            return response()->json($messages);
        });
        Route::post('/chat-sync/{id}/send', function (Request $request, $id) {
            ChatMessage::query()->create([
                'session_id' => $id,
                'user_id' => Auth::id() ?? 1,
                'message' => $request->message,
                'sender_type' => 'admin',
            ]);
            ChatSession::query()->where('id', $id)->update(['updated_at' => now()]);
            return response()->json(['success' => true]);
        });
    });
});
// route api kustomer (diluar middleware)
// api new session
Route::post('/chat-api/create', function () {
    $session = ChatSession::create([
        'customer_name' => 'Guest A\'paweh ' . rand(1000, 9999),
        'status' => 'open'
    ]);
    return response()->json(['success' => true, 'session_id' => $session->id]);
});
// api send messages
Route::post('/chat-api/{sessionId}/send', function (Request $request, $sessionId) {
    ChatMessage::query()->create([
        'session_id' => $sessionId,
        'user_id' => null,
        'message' => $request->message,
        'sender_type' => 'customer' // 'customer'
    ]);
    ChatSession::query()->where('id', $sessionId)->update(['updated_at' => now()]);
    return response()->json(['success' => true]);
});
// api check reply from admin
Route::get('/chat-api/{sessionId}/messages', function (Request $request, $sessionId) {
    $session = ChatSession::query()->find($sessionId);
    if (!$session) return response()->json(['session_status' => 'closed']);
    $lastId = $request->query('last_id', 0);
    $newMessages = ChatMessage::query()
        ->where('session_id', $sessionId)
        ->where('id', '>', $lastId)
        ->orderBy('id', 'asc')
        ->get();
    return response()->json([
        'session_status' => $session->status,
        'new_messages' => $newMessages
    ]);
});
/**
 * Catch-all 404 handler (override Laravel default)
 * Render page custom: resources/views/pages/page404.blade.php
 */
Route::any('/{any}', function () {
    return response()->view('pages.page404', [], 404);
})->where('any', '.*');

/**
 * api close session
 */
Route::post('/chat-api/{sessionId}/close', function ($sessionId) {
    ChatSession::query()->where('id', $sessionId)->update(['status' => 'closed']);
    return response()->json(['success' => true]);
});