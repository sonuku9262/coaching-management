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
        Schema::create('teacher_salary_payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('teacher_id')->constrained()->cascadeOnDelete();

            $table->date('salary_month');

            $table->decimal('amount', 10, 2);

            $table->decimal('deduction', 10, 2)->default(0);

            $table->decimal('paid_amount', 10, 2);

            $table->date('payment_date');

            $table->string('payment_mode')->default('Cash');

            $table->string('voucher_no')->unique();

            $table->text('remarks')->nullable();

            $table->timestamps();

            $table->unique(['teacher_id', 'salary_month'], 'salary_payment_teacher_month_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_salary_payments');
    }
};
