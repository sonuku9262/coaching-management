<?php

namespace App\Livewire\Portal\ParentPortal;

use App\Livewire\Portal\ParentPortal\Concerns\ResolvesChild;
use Livewire\Component;

class Attendance extends Component
{
    use ResolvesChild;

    public $month;

    public function mount()
    {
        $this->month = now()->format('Y-m');
    }

    public function render()
    {
        $children = $this->children();
        $student = $this->resolveChild($children);

        if ($student) {
            $this->student_id = $student->id;
        }

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

        return view('livewire.portal.parent.attendance', [
            'children' => $children,
            'student' => $student,
            'records' => $records,
            'summary' => $summary,
        ])->layout('layouts.admin');
    }
}
