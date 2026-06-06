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
        $data = $request->validate([
            'product_name' => 'required|string',
            'price' => 'required|numeric',
            'quantity' => 'required|integer|min:1',
            'whatsapp' => 'required|string|regex:/^[0-9]+$/|min:8|max:15',
            'customer_email' => 'nullable|email',
            'shipping_address' => 'required|string|max:200',
            'customer_name' => 'required|string|max:50',
            'payment_method' => 'required|string',
        ]);

        $product = $data['product_name'];
        $price = (float) $data['price'];
        $quantity = (int) $data['quantity'];
        $total = $price * $quantity;
        $shipping = 6000;
        $admin_fee = 1000;
        $total_amount = $total + $shipping + $admin_fee;

        $productId = session('checkout_product_id');
        $productModel = $productId ? \App\Models\Product::find($productId) : null;

        return view(
            'pages.checkout',
            compact('product', 'quantity', 'price', 'total', 'productModel'),
        );
    }
}
