<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enquiry extends Model
{
    protected $fillable = [
        'name',
        'mobile',
        'email',
        'course_id',
        'message',
        'status',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
