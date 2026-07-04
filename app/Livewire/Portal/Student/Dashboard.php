<?php

namespace App\Livewire\Portal\Student;

use App\Models\StudentRegistration;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $student = StudentRegistration::with(['course', 'batch', 'shift'])
            ->where('user_id', auth()->id())
            ->first();

        $attendance = null;
        $recentAttendance = collect();
        $feeSummary = null;
        $recentFees = collect();
        $recentResults = collect();

        if ($student) {
            $attendance = [
                'present' => $student->attendances()->where('status', 'Present')->count(),
                'absent' => $student->attendances()->where('status', 'Absent')->count(),
                'total' => $student->attendances()->count(),
            ];

            $recentAttendance = $student->attendances()
                ->orderByDesc('attendance_date')
                ->take(10)
                ->get();

            $feeSummary = [
                'paid' => $student->feeCollections()->sum('paid_amount'),
                'balance' => $student->feeCollections()->sum('balance'),
            ];

            $recentFees = $student->feeCollections()
                ->with('feeType')
                ->orderByDesc('payment_date')
                ->take(5)
                ->get();

            $recentResults = $student->examResults()
                ->with(['schedule.exam', 'schedule.subject'])
                ->latest()
                ->take(10)
                ->get();
        }

        return view('livewire.portal.student.dashboard', [
            'student' => $student,
            'attendance' => $attendance,
            'recentAttendance' => $recentAttendance,
            'feeSummary' => $feeSummary,
            'recentFees' => $recentFees,
            'recentResults' => $recentResults,
        ])->layout('layouts.admin');
    }
}
