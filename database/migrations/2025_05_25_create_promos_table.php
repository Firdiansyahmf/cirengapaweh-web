<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('promos')) {
            Schema::create('promos', function (Blueprint $table) {
                $table->id();
                $table->string('title', 150);
                $table->string('promo_code', 50)->nullable()->unique();
                $table->enum('promo_type', ['otomatis', 'kode'])->default('otomatis');
                $table->integer('discount_percentage');
                $table->text('description')->nullable();
                $table->integer('max_usage')->default(0);
                $table->integer('used_count')->default(0);
                $table->date('start_date');
                $table->date('end_date');
                $table->boolean('is_active')->default(true);
                $table->timestamps();
                $table->unique('promo_code');
            });
        }

        if (!Schema::hasTable('promo_products')) {
            Schema::create('promo_products', function (Blueprint $table) {
                $table->id();
                $table->foreignId('promo_id')->constrained()->onDelete('cascade');
                $table->foreignId('product_id')->constrained()->onDelete('cascade');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('promo_products')) {
            Schema::dropIfExists('promo_products');
        }
        if (Schema::hasTable('promos')) {
            Schema::dropIfExists('promos');
        }
    }
};