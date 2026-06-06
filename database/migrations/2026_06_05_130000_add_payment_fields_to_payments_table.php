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
        Schema::table('payments', function (Blueprint $table) {
            $table->string('bank', 50)->nullable()->after('payment_type');
            $table->string('va_number', 100)->nullable()->after('bank');
            $table->string('biller_code', 50)->nullable()->after('va_number');
            $table->text('qr_code_url')->nullable()->after('biller_code');
            $table->timestamp('expiry_time')->nullable()->after('qr_code_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn([
                'bank',
                'va_number',
                'biller_code',
                'qr_code_url',
                'expiry_time'
            ]);
        });
    }
};
