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
        Schema::create('class_timetables', function (Blueprint $table) {
            $table->id();

            $table->foreignId('batch_id')->constrained()->cascadeOnDelete();

            $table->foreignId('subject_id')->constrained()->cascadeOnDelete();

            $table->foreignId('teacher_id')->nullable()->constrained()->nullOnDelete();

            $table->foreignId('classroom_id')->nullable()->constrained()->nullOnDelete();

            $table->enum('day_of_week', [
                'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday',
            ]);

            $table->time('start_time');

            $table->time('end_time');

            $table->timestamps();

            $table->unique(['batch_id', 'day_of_week', 'start_time'], 'timetable_batch_slot_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_timetables');
    }
};
