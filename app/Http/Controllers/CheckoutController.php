<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function prepare(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer',
            'product_name' => 'required|string',
            'price' => 'required|numeric',
            'quantity' => 'required|integer|min:1',
            'promo_id' => 'nullable|integer|exists:promos,id',
        ]);

        session([
            'checkout_product_id' => $request->product_id,
            'checkout_product' => $request->product_name,
            'checkout_price' => (float) $request->price,
            'checkout_quantity' => (int) $request->quantity,
            'checkout_promo_id' => $request->promo_id,
        ]);

        return redirect('/checkout');
    }

    public function show(Request $request)
    {
        $product = session('checkout_product', 'Pilih Produk Dahulu');
        $price = session('checkout_price', 0);
        $quantity = session('checkout_quantity', 1);
        $total = $price * $quantity;

        $productId = session('checkout_product_id');
        $productModel = $productId ? \App\Models\Product::find($productId) : null;

        $promoId = session('checkout_promo_id');
        $promo = $promoId ? \App\Models\Promo::find($promoId) : null;

        return view(
            'pages.checkout',
            compact('product', 'quantity', 'price', 'total', 'productModel', 'promo'),
        );
    }

    public function validatePromo(Request $request)
    {
        $request->validate([
            'promo_code' => 'required|string',
        ]);

        $productId = session('checkout_product_id');
        if (!$productId) {
            return response()->json([
                'success' => false,
                'message' => 'Sesi produk tidak ditemukan. Silakan pilih produk kembali.'
            ], 400);
        }

        $promo = \App\Models\Promo::where('promo_code', $request->promo_code)
            ->where('is_active', true)
            ->first();

        if (!$promo) {
            return response()->json([
                'success' => false,
                'message' => 'Promo tidak ditemukan atau tidak aktif.'
            ], 400);
        }

        if (!$promo->isStarted()) {
            return response()->json([
                'success' => false,
                'message' => 'Periode promo belum dimulai.'
            ], 400);
        }

        if ($promo->isExpired()) {
            return response()->json([
                'success' => false,
                'message' => 'Promo sudah kadaluarsa.'
            ], 400);
        }

        if ($promo->used_count >= $promo->max_usage) {
            return response()->json([
                'success' => false,
                'message' => 'Kuota promo sudah habis.'
            ], 400);
        }

        $hasProduct = $promo->products()->where('products.id', $productId)->exists();
        if (!$hasProduct) {
            return response()->json([
                'success' => false,
                'message' => 'Promo ini tidak berlaku untuk produk ini.'
            ], 400);
        }

        session(['checkout_promo_id' => $promo->id]);

        $price = session('checkout_price', 0);
        $quantity = session('checkout_quantity', 1);
        $subtotal = $price * $quantity;
        $discount = ($subtotal * $promo->discount_percentage) / 100;

        return response()->json([
            'success' => true,
            'message' => 'Promo berhasil dipakai',
            'promo_code' => $promo->promo_code,
            'discount_percentage' => $promo->discount_percentage,
            'discount_amount' => $discount,
        ]);
    }

    public function store(Request $request)
    {
        return app(\App\Http\Controllers\PaymentController::class)->processPayment($request);
    }
}
