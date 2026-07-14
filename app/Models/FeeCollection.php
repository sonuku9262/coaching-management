<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\Activitylog\Models\Concerns\LogsActivity;

class FeeCollection extends Model
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
        'student_registration_id',
        'fee_type_id',
        'amount',
        'discount',
        'fine',
        'paid_amount',
        'balance',
        'payment_mode',
        'gateway_order_id',
        'gateway_payment_id',
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