<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Tabel promos SUDAH ADA di database
        // Jadi tidak perlu create ulang

        if (!Schema::hasTable('promo_products')) {
            Schema::create('promo_products', function (Blueprint $table) {
                $table->id();

                $table->unsignedBigInteger('promo_id');
                $table->unsignedBigInteger('product_id');

                $table->timestamps();

                $table->foreign('promo_id')
                    ->references('id')
                    ->on('promos')
                    ->onDelete('cascade');

                $table->foreign('product_id')
                    ->references('id')
                    ->on('products')
                    ->onDelete('cascade');

                $table->unique(['promo_id', 'product_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('promo_products');
    }
};