<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentRegistration extends Model
{
    protected $fillable = [

        'admission_no',

        'academic_year_id',

        'academic_session_id',

        'course_id',

        'batch_id',

        'classroom_id',

        'shift_id',

        'name',

        'father_name',

        'mother_name',

        'gender',

        'dob',

        'mobile',

        'email',

        'address',

        'admission_date',

        'photo',

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

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }
}