<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Course;
use App\Models\Subject;
use App\Models\Batch;
use App\Models\Teacher;
use App\Models\StudentRegistration;
use App\Models\FeeCollection;
use App\Models\StudentAttendance;
use App\Models\Exam;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $route = $user->dashboardRoute();

        // users without any role wait for the admin to assign one
        if ($route === 'home') {
            return redirect()->route('home')
                ->with('info', 'Aapka account abhi approve nahi hua hai. Institute se sampark karein.');
        }

        // portal roles land on their own dashboard
        if ($route !== 'dashboard') {
            return redirect()->route($route);
        }

        return view('dashboard', [

            'students' => StudentRegistration::count(),

            'teachers' => Teacher::count(),

            'courses' => Course::count(),

            'subjects' => Subject::count(),

            'batches' => Batch::count(),

            'users' => User::count(),

            'todayCollection' => FeeCollection::whereDate('payment_date', today())
                ->sum('paid_amount'),

            'totalCollection' => FeeCollection::sum('paid_amount'),

            'totalDues' => FeeCollection::sum('balance'),

            'todayPresent' => StudentAttendance::whereDate('attendance_date', today())
                ->where('status', 'Present')
                ->count(),

            'todayAbsent' => StudentAttendance::whereDate('attendance_date', today())
                ->where('status', 'Absent')
                ->count(),

            'activeExams' => Exam::where('status', true)->count(),

            'collectionChart' => $this->monthlyCollections(),

            'attendanceChart' => $this->attendanceTrend(),

            'courseChart' => $this->studentsPerCourse(),

            'recentStudents' => StudentRegistration::latest()
                ->take(5)
                ->get(),

            'recentFees' => FeeCollection::with('student')
                ->latest()
                ->take(5)
                ->get(),

        ]);
    }

    /**
     * Fee collection totals for the last 6 months.
     */
    protected function monthlyCollections(): array
    {
        $labels = [];
        $values = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);

            $labels[] = $month->format('M Y');

            $values[] = (float) FeeCollection::whereBetween('payment_date', [
                $month->copy()->startOfMonth()->toDateString(),
                $month->copy()->endOfMonth()->toDateString(),
            ])->sum('paid_amount');
        }

        return ['labels' => $labels, 'values' => $values];
    }

    /**
     * Present/absent counts for the last 14 days.
     */
    protected function attendanceTrend(): array
    {
        $labels = [];
        $present = [];
        $absent = [];

        $rows = StudentAttendance::selectRaw('attendance_date, status, count(*) as total')
            ->whereBetween('attendance_date', [now()->subDays(13)->toDateString(), now()->toDateString()])
            ->groupBy('attendance_date', 'status')
            ->get()
            ->groupBy('attendance_date');

        for ($i = 13; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $day = $rows->get($date->toDateString(), collect());

            $labels[] = $date->format('d M');
            $present[] = (int) $day->firstWhere('status', 'Present')?->total;
            $absent[] = (int) $day->firstWhere('status', 'Absent')?->total;
        }

        return ['labels' => $labels, 'present' => $present, 'absent' => $absent];
    }

    /**
     * Active student count per course.
     */
    protected function studentsPerCourse(): array
    {
        $rows = StudentRegistration::selectRaw('course_id, count(*) as total')
            ->where('status', 1)
            ->groupBy('course_id')
            ->get();

        $courses = Course::whereIn('id', $rows->pluck('course_id'))->pluck('name', 'id');

        return [
            'labels' => $rows->map(fn ($row) => $courses->get($row->course_id, 'Other'))->values()->all(),
            'values' => $rows->pluck('total')->values()->all(),
        ];
    }
}