<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\PaymentController;
use App\Models\Promo;
use App\Models\Product;

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
        $productModel = $productId ? Product::find($productId) : null;

        $promoId = session('checkout_promo_id');
        $promo = $promoId ? Promo::find($promoId) : null;

        return view(
            'pages.checkout',
            compact('product', 'quantity', 'price', 'total', 'productModel', 'promo'),
        );
    }

    public function store(Request $request)
    {
        return app(PaymentController::class)->processPayment($request);
    }
}
