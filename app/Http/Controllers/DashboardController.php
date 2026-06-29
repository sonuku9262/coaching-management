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