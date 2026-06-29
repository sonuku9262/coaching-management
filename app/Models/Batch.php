<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    protected $fillable = [
        'course_id',
        'name',
        'start_date',
        'end_date',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
