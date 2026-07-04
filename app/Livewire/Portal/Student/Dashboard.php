<?php

namespace App\Livewire\Portal\Student;

use App\Models\ExamSchedule;
use App\Models\Notice;
use App\Models\StudentRegistration;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $student = StudentRegistration::with(['course', 'batch', 'shift', 'classroom'])
            ->where('user_id', auth()->id())
            ->first();

        $attendance = null;
        $monthAttendance = null;
        $feeSummary = null;
        $recentResults = collect();
        $upcomingExams = collect();

        if ($student) {
            $attendance = [
                'present' => $student->attendances()->where('status', 'Present')->count(),
                'absent' => $student->attendances()->where('status', 'Absent')->count(),
                'total' => $student->attendances()->count(),
            ];

            $monthQuery = $student->attendances()
                ->whereBetween('attendance_date', [now()->startOfMonth()->toDateString(), now()->toDateString()]);

            $monthAttendance = [
                'present' => (clone $monthQuery)->where('status', 'Present')->count(),
                'total' => (clone $monthQuery)->count(),
            ];

            $feeSummary = [
                'paid' => $student->feeCollections()->sum('paid_amount'),
                'balance' => $student->feeCollections()->sum('balance'),
            ];

            $recentResults = $student->examResults()
                ->with(['schedule.exam', 'schedule.subject'])
                ->latest()
                ->take(5)
                ->get();

            $upcomingExams = ExamSchedule::with(['exam', 'subject'])
                ->whereHas('exam', function ($query) use ($student) {
                    $query->where('status', 1)
                        ->where('course_id', $student->course_id)
                        ->where('batch_id', $student->batch_id);
                })
                ->whereDate('exam_date', '>=', today())
                ->orderBy('exam_date')
                ->take(5)
                ->get();
        }

        $notices = Notice::where('status', 1)
            ->orderByDesc('notice_date')
            ->take(5)
            ->get();

        return view('livewire.portal.student.dashboard', [
            'student' => $student,
            'attendance' => $attendance,
            'monthAttendance' => $monthAttendance,
            'feeSummary' => $feeSummary,
            'recentResults' => $recentResults,
            'upcomingExams' => $upcomingExams,
            'notices' => $notices,
        ])->layout('layouts.admin');
    }
}
