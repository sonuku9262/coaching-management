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
        Schema::create('study_materials', function (Blueprint $table) {
            $table->id();

            $table->foreignId('batch_id')->constrained()->cascadeOnDelete();

            $table->foreignId('subject_id')->constrained()->cascadeOnDelete();

            $table->foreignId('teacher_id')->nullable()->constrained()->nullOnDelete();

            $table->string('title');

            $table->text('description')->nullable();

            $table->string('file_path')->nullable();

            $table->string('file_name')->nullable();

            $table->boolean('status')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('study_materials');
    }
};
