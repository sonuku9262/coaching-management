<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $fillable = [
        'employee_id',
        'name',
        'mobile',
        'email',
        'qualification',
        'experience',
        'photo',
        'address',
        'joining_date',
        'salary',
        'status',
    ];
}