<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function show(Request $request)
    {
        $product = $request->query('product', 'Cireng Kuah Keju Juara!');
        $quantity = (int) $request->query('quantity', 1);
        $price = (float) $request->query('price', 15000);
        $total = $price * $quantity;

        return view('pages.checkout', compact('product', 'quantity', 'price', 'total'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'product_name' => 'required|string',
            'price' => 'required|numeric',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = $data['product_name'];
        $price = (float) $data['price'];
        $quantity = (int) $data['quantity'];
        $total = $price * $quantity;

        return view('pages.checkout', compact('product', 'quantity', 'price', 'total'));
    }
}
