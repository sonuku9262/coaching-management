<?php

namespace App\Livewire\Portal\ParentPortal;

use App\Models\StudentRegistration;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $children = StudentRegistration::with(['course', 'batch'])
            ->where('guardian_user_id', auth()->id())
            ->get()
            ->map(function ($child) {
                $total = $child->attendances()->count();
                $present = $child->attendances()->where('status', 'Present')->count();

                return [
                    'student' => $child,
                    'attendancePercent' => $total ? round($present / $total * 100) : null,
                    'paid' => $child->feeCollections()->sum('paid_amount'),
                    'balance' => $child->feeCollections()->sum('balance'),
                ];
            });

        return view('livewire.portal.parent.dashboard', [
            'children' => $children,
        ])->layout('layouts.admin');
    }
}
