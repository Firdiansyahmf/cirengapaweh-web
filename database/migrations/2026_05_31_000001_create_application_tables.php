<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('products')) {
            Schema::create('products', function (Blueprint $table) {
                $table->id();
                $table->string('name', 150);
                $table->text('description')->nullable();
                $table->string('image', 255)->nullable();
                $table->enum('category', ['fast_food', 'frozen_food']);
                $table->unsignedInteger('price');
                $table->boolean('is_active')->default(true);
                $table->timestamps();
                $table->index('category');
            });
        }

        if (!Schema::hasTable('partner_locations')) {
            Schema::create('partner_locations', function (Blueprint $table) {
                $table->id();
                $table->string('name', 150);
                $table->string('image', 255)->nullable();
                $table->text('address');
                $table->string('operating_hours', 100);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('orders')) {
            Schema::create('orders', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
                $table->string('invoice_number', 100)->unique();
                $table->string('customer_name', 100);
                $table->string('customer_email', 100);
                $table->string('customer_phone', 20);
                $table->text('shipping_address');
                $table->unsignedInteger('total_amount');
                $table->enum('status', ['unpaid', 'paid', 'packing', 'shipping', 'completed', 'cancelled'])->default('unpaid');
                $table->timestamps();
                $table->index('invoice_number');
                $table->index('status');
            });
        }

        if (!Schema::hasTable('order_items')) {
            Schema::create('order_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('order_id')->constrained()->onDelete('cascade');
                $table->foreignId('product_id')->constrained()->onDelete('cascade');
                $table->unsignedInteger('quantity');
                $table->unsignedInteger('unit_price');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('order_status_histories')) {
            Schema::create('order_status_histories', function (Blueprint $table) {
                $table->id();
                $table->foreignId('order_id')->constrained()->onDelete('cascade');
                $table->enum('status', ['unpaid', 'paid', 'packing', 'shipping', 'completed', 'cancelled']);
                $table->text('notes')->nullable();
                $table->timestamp('changed_at')->useCurrent();
            });
        }

        if (!Schema::hasTable('deliveries')) {
            Schema::create('deliveries', function (Blueprint $table) {
                $table->id();
                $table->foreignId('order_id')->unique()->constrained()->onDelete('cascade');
                $table->string('courier_name', 100)->nullable();
                $table->string('tracking_number', 100)->nullable();
                $table->enum('status', ['preparing', 'picked_up', 'on_delivery', 'delivered'])->default('preparing');
                $table->timestamps();
                $table->index('status');
            });
        }

        if (!Schema::hasTable('payments')) {
            Schema::create('payments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('order_id')->unique()->constrained()->onDelete('cascade');
                $table->string('transaction_id', 100);
                $table->string('payment_type', 50);
                $table->unsignedInteger('amount');
                $table->enum('status', ['pending', 'settlement', 'expire', 'deny', 'cancel', 'refund'])->default('pending');
                $table->timestamps();
                $table->index('status');
            });
        }

        if (!Schema::hasTable('chat_sessions')) {
            Schema::create('chat_sessions', function (Blueprint $table) {
                $table->id();
                $table->string('customer_name', 100);
                $table->string('customer_phone', 20);
                $table->enum('status', ['open', 'closed'])->default('open');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('chat_messages')) {
            Schema::create('chat_messages', function (Blueprint $table) {
                $table->id();
                $table->foreignId('session_id')->constrained('chat_sessions')->onDelete('cascade');
                $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
                $table->enum('sender_type', ['bot', 'customer', 'admin']);
                $table->text('message');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_messages');
        Schema::dropIfExists('chat_sessions');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('deliveries');
        Schema::dropIfExists('order_status_histories');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('partner_locations');
        Schema::dropIfExists('products');
    }
};
