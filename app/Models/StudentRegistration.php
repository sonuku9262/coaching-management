<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\Activitylog\Models\Concerns\LogsActivity;

class StudentRegistration extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontLogEmptyChanges();
    }

    protected $fillable = [

        'user_id',

        'guardian_email',

        'guardian_user_id',

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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function guardian()
    {
        return $this->belongsTo(User::class, 'guardian_user_id');
    }

    public function attendances()
    {
        return $this->hasMany(StudentAttendance::class);
    }

    public function feeCollections()
    {
        return $this->hasMany(FeeCollection::class);
    }

    public function examResults()
    {
        return $this->hasMany(ExamResult::class);
    }
}