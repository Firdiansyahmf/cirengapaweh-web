<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign('orders_user_id_foreign');
            $table->dropColumn('user_id');

            $table->unsignedInteger('subtotal_amount')->after('shipping_address');
            $table->unsignedInteger('shipping_cost')->after('subtotal_amount');
            $table->string('postal_code', 10)->nullable()->after('shipping_cost');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('postal_code');
            $table->dropColumn('shipping_cost');
            $table->dropColumn('subtotal_amount');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
        });
    }
};
