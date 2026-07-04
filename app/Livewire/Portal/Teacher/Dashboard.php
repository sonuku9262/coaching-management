<?php

namespace App\Livewire\Portal\Teacher;

use App\Models\Teacher;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $teacher = Teacher::where('user_id', auth()->id())->first();

        $monthAttendance = null;
        $recentAttendance = collect();

        if ($teacher) {
            $monthQuery = $teacher->attendances()
                ->whereBetween('attendance_date', [now()->startOfMonth()->toDateString(), now()->toDateString()]);

            $monthAttendance = [
                'present' => (clone $monthQuery)->where('status', 'Present')->count(),
                'absent' => (clone $monthQuery)->where('status', 'Absent')->count(),
                'total' => (clone $monthQuery)->count(),
            ];

            $recentAttendance = $teacher->attendances()
                ->orderByDesc('attendance_date')
                ->take(10)
                ->get();
        }

        return view('livewire.portal.teacher.dashboard', [
            'teacher' => $teacher,
            'monthAttendance' => $monthAttendance,
            'recentAttendance' => $recentAttendance,
        ])->layout('layouts.admin');
    }
}
