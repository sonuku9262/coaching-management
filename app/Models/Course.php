<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'name',
        'code',
        'duration',
        'duration_type',
        'fees',
        'description',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
        

    ];

    public function subjects()
    {
        return $this->hasMany(CourseSubject::class);
    }

    public function batches()
    {
        return $this->hasMany(Batch::class);
    }
}
