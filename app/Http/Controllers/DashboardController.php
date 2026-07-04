<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Course;
use App\Models\Subject;
use App\Models\Batch;
use App\Models\Teacher;
use App\Models\StudentRegistration;
use App\Models\FeeCollection;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // portal-only users land on their own dashboard
        if ($user->hasRole('teacher')) {
            return redirect()->route('teacher.dashboard');
        }

        if ($user->hasRole('student')) {
            return redirect()->route('student.dashboard');
        }

        if ($user->hasRole('parent')) {
            return redirect()->route('parent.dashboard');
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