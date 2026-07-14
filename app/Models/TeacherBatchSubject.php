<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherBatchSubject extends Model
{
    protected $fillable = [
        'teacher_id',
        'batch_id',
        'subject_id',
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
