<?php

namespace App\Livewire\Portal\ParentPortal;

use App\Livewire\Portal\ParentPortal\Concerns\ResolvesChild;
use Livewire\Component;

class Fees extends Component
{
    use ResolvesChild;

    public function render()
    {
        $children = $this->children();
        $student = $this->resolveChild($children);

        if ($student) {
            $this->student_id = $student->id;
        }

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

        return view('livewire.portal.parent.fees', [
            'children' => $children,
            'student' => $student,
            'payments' => $payments,
            'summary' => $summary,
        ])->layout('layouts.admin');
    }
}
