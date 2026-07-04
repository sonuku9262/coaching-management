<?php

namespace App\Livewire\Portal\Student;

use App\Models\StudentRegistration;
use Livewire\Component;

class Attendance extends Component
{
    public $month;

    public function mount()
    {
        $this->month = now()->format('Y-m');
    }

    public function render()
    {
        $student = StudentRegistration::where('user_id', auth()->id())->first();

        $records = collect();
        $summary = null;

        if ($student && $this->month) {
            $start = $this->month . '-01';
            $end = date('Y-m-t', strtotime($start));

            $records = $student->attendances()
                ->whereBetween('attendance_date', [$start, $end])
                ->orderByDesc('attendance_date')
                ->get();

            $summary = [
                'present' => $records->where('status', 'Present')->count(),
                'absent' => $records->where('status', 'Absent')->count(),
                'leave' => $records->where('status', 'Leave')->count(),
                'total' => $records->count(),
            ];
        }

        return view('livewire.portal.student.attendance', [
            'student' => $student,
            'records' => $records,
            'summary' => $summary,
        ])->layout('layouts.admin');
    }
}
