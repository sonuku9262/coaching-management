<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('batches', function (Blueprint $table) {

            $table->foreignId('academic_year_id')
                  ->after('id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->foreignId('academic_session_id')
                  ->after('academic_year_id')
                  ->constrained()
                  ->cascadeOnDelete();

        });
    }

    public function down(): void
    {
        Schema::table('batches', function (Blueprint $table) {

            $table->dropConstrainedForeignId('academic_year_id');
            $table->dropConstrainedForeignId('academic_session_id');

        });
    }
};