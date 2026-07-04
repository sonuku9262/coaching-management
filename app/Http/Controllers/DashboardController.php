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

            'recentStudents' => StudentRegistration::latest()
                ->take(5)
                ->get(),

            'recentFees' => FeeCollection::with('student')
                ->latest()
                ->take(5)
                ->get(),

        ]);
    }
}