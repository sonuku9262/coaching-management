<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\Activitylog\Models\Concerns\LogsActivity;

class TeacherSalaryPayment extends Model
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
        'teacher_id',
        'salary_month',
        'amount',
        'deduction',
        'paid_amount',
        'payment_date',
        'payment_mode',
        'voucher_no',
        'remarks',
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}
