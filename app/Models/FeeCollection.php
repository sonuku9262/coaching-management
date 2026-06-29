<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeeCollection extends Model
{
    protected $fillable = [
        'student_registration_id',
        'fee_type_id',
        'amount',
        'discount',
        'fine',
        'paid_amount',
        'balance',
        'payment_mode',
        'receipt_no',
        'payment_date',
        'remarks',
        'status',
    ];

    public function student()
    {
        return $this->belongsTo(StudentRegistration::class, 'student_registration_id');
    }

    public function feeType()
    {
        return $this->belongsTo(FeeType::class);
    }
}