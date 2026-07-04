<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentAttendance extends Model
{
    protected $fillable = [
        'student_registration_id',
        'course_id',
        'batch_id',
        'attendance_date',
        'status',
        'remarks',
    ];

    public function student()
    {
        return $this->belongsTo(StudentRegistration::class, 'student_registration_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }
}
