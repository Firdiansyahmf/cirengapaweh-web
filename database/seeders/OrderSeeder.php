<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get a product for the order item
        $product = Product::where('is_active', true)->first();
        
        if (!$product) {
            $this->command->warn('No products found. Run ProductSeeder first!');
            return;
        }

        // Create a single test order
        $order = Order::create([
            'invoice_number' => 'INV-' . date('Ymd') . '-0010',
            'customer_name' => 'Cahya',
            'customer_email' => 'cahya@example.com',
            'customer_phone' => '08123456789',
            'shipping_address' => 'Jl. Merdeka No. 123, Bandung',
            'postal_code' => '44162',
            'subtotal_amount' => 50000,
            'shipping_cost' => 10000,
            'total_amount' => 60000,
            'status' => 'paid',
        ]);

        // Add a single order item
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'unit_price' => $product->price,
        ]);

        // Create payment record
        Payment::create([
            'order_id' => $order->id,
            'transaction_id' => 'TXN-' . Str::random(20),
            'payment_type' => 'credit_card',
            'amount' => 60000,
            'snap_token' => 'snap_' . Str::random(30),
            'payment_url' => 'https://app.sandbox.midtrans.com/snap/v1/' . Str::random(50),
            'status' => 'settlement',
        ]);

        $this->command->info('✓ Test order created: INV-' . date('Ymd') . '-0006');
        $this->command->info('  Status: PAID - Ready to accept in admin panel');
        $this->command->info('  Biteship shipment will be sent when you click "Terima Pesanan"');
    }
}

