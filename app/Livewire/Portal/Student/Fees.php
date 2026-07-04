<?php

namespace App\Livewire\Portal\Student;

use App\Models\StudentRegistration;
use Livewire\Component;

class Fees extends Component
{
    public function render()
    {
        $student = StudentRegistration::with('course')
            ->where('user_id', auth()->id())
            ->first();

        $payments = collect();
        $summary = null;

        if ($student) {
            $payments = $student->feeCollections()
                ->with('feeType')
                ->orderByDesc('payment_date')
                ->get();

            $summary = [
                'amount' => $payments->sum('amount'),
                'discount' => $payments->sum('discount'),
                'fine' => $payments->sum('fine'),
                'paid' => $payments->sum('paid_amount'),
                'balance' => $payments->sum('balance'),
            ];
        }

        return view('livewire.portal.student.fees', [
            'student' => $student,
            'payments' => $payments,
            'summary' => $summary,
        ])->layout('layouts.admin');
    }
}
