<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\Activitylog\Models\Concerns\LogsActivity;

class ExamResult extends Model
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
        'exam_schedule_id',
        'student_registration_id',
        'marks_obtained',
        'is_absent',
        'remarks',
    ];

    protected $casts = [
        'is_absent' => 'boolean',
    ];

    public function schedule()
    {
        return $this->belongsTo(ExamSchedule::class, 'exam_schedule_id');
    }

    public function student()
    {
        return $this->belongsTo(StudentRegistration::class, 'student_registration_id');
    }

    public function isPass(): ?bool
    {
        if ($this->is_absent || $this->marks_obtained === null) {
            return false;
        }

        return $this->marks_obtained >= $this->schedule->passing_marks;
    }
}
