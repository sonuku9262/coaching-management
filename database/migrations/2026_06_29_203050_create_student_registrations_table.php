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
        Schema::create('student_registrations', function (Blueprint $table) {

            $table->id();

            $table->string('admission_no')->unique();

            $table->foreignId('academic_year_id')->constrained()->cascadeOnDelete();

            $table->foreignId('academic_session_id')->constrained()->cascadeOnDelete();

            $table->foreignId('course_id')->constrained()->cascadeOnDelete();

            $table->foreignId('batch_id')->constrained()->cascadeOnDelete();

            $table->foreignId('classroom_id')->constrained()->cascadeOnDelete();

            $table->foreignId('shift_id')->constrained()->cascadeOnDelete();

            $table->string('name');

            $table->string('father_name');

            $table->string('mother_name')->nullable();

            $table->enum('gender', ['Male', 'Female', 'Other']);

            $table->date('dob');

            $table->string('mobile', 15);

            $table->string('email')->nullable();

            $table->text('address');

            $table->date('admission_date');

            $table->string('photo')->nullable();

            $table->boolean('status')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_registrations');
    }
};
