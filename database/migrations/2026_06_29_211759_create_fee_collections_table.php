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
        Schema::create('fee_collections', function (Blueprint $table) {

            $table->id();

            $table->foreignId('student_registration_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('fee_type_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->decimal('amount', 10, 2);

            $table->decimal('discount', 10, 2)->default(0);

            $table->decimal('fine', 10, 2)->default(0);

            $table->decimal('paid_amount', 10, 2);

            $table->decimal('balance', 10, 2)->default(0);

            $table->string('payment_mode');

            $table->string('receipt_no')->unique();

            $table->date('payment_date');

            $table->text('remarks')->nullable();

            $table->boolean('status')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fee_collections');
    }
};
