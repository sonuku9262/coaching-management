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
        Schema::table('fee_collections', function (Blueprint $table) {
            $table->string('gateway_order_id')->nullable()->after('payment_mode');
            $table->string('gateway_payment_id')->nullable()->after('gateway_order_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fee_collections', function (Blueprint $table) {
            $table->dropColumn(['gateway_order_id', 'gateway_payment_id']);
        });
    }
};
