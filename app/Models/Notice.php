<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    protected $fillable = [
        'title',
        'description',
        'notice_date',
        'show_on_website',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
        'show_on_website' => 'boolean',
    ];
}
