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
        Schema::table('deliveries', function (Blueprint $table) {
            $table->string('biteship_order_id', 100)->nullable()->after('order_id');
            $table->string('courier_service', 100)->nullable()->after('courier_name');
            $table->string('status')->change()->default('preparing');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('deliveries', function (Blueprint $table) {
            $table->dropColumn('courier_service');
            $table->dropColumn('biteship_order_id');
        });
    }
};
