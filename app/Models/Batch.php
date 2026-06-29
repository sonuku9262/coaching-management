<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    protected $fillable = [
        'academic_year_id',
        'academic_session_id',
        'course_id',
        'name',
        'start_date',
        'end_date',
        'capacity',
        'status',
    ];

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function academicSession()
    {
        return $this->belongsTo(AcademicSession::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
