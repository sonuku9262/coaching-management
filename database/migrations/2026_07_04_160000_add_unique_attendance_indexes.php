<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // remove duplicates left behind by the old save logic (keep latest row)
        $keepIds = DB::table('student_attendances')
            ->selectRaw('MAX(id) as id')
            ->groupBy('student_registration_id', 'attendance_date')
            ->pluck('id');

        DB::table('student_attendances')->whereNotIn('id', $keepIds)->delete();

        Schema::table('student_attendances', function (Blueprint $table) {
            $table->unique(['student_registration_id', 'attendance_date'], 'student_attendance_unique_day');
        });

        Schema::table('teacher_attendances', function (Blueprint $table) {
            $table->unique(['teacher_id', 'attendance_date'], 'teacher_attendance_unique_day');
        });
    }

    public function down(): void
    {
        Schema::table('student_attendances', function (Blueprint $table) {
            $table->dropUnique('student_attendance_unique_day');
        });

        Schema::table('teacher_attendances', function (Blueprint $table) {
            $table->dropUnique('teacher_attendance_unique_day');
        });
    }
};
