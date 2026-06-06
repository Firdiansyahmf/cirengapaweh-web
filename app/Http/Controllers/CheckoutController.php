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
        ]);

        session([
            'checkout_product_id' => $request->product_id,
            'checkout_product' => $request->product_name,
            'checkout_price' => (float) $request->price,
            'checkout_quantity' => (int) $request->quantity,
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

        return view(
            'pages.checkout',
            compact('product', 'quantity', 'price', 'total', 'productModel'),
        );
    }

    public function store(Request $request)
    {
        return app(\App\Http\Controllers\PaymentController::class)->processPayment($request);
    }
}
