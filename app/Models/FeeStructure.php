<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeeStructure extends Model
{
    protected $fillable = [
        'course_id',
        'fee_type_id',
        'amount',
        'installments',
        'status',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function feeType()
    {
        return $this->belongsTo(FeeType::class);
    }
}