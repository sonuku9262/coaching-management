<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Course;
use App\Models\Enquiry;
use App\Models\Setting;
use App\Models\StudentRegistration;
use App\Models\Teacher;
use App\Notifications\EnquiryReceived;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class FrontendController extends Controller
{
    public function home()
    {
        return view('frontend.home', [
            'courses' => Course::where('status', 1)->latest()->take(6)->get(),
            'teachers' => Teacher::where('status', 1)->latest()->take(4)->get(),
            'stats' => [
                'students' => StudentRegistration::count(),
                'teachers' => Teacher::count(),
                'courses' => Course::where('status', 1)->count(),
                'batches' => Batch::where('status', 1)->count(),
            ],
        ]);
    }

    public function about()
    {
        return view('frontend.about', [
            'teachers' => Teacher::where('status', 1)->latest()->take(8)->get(),
        ]);
    }

    public function courses()
    {
        return view('frontend.courses', [
            'courses' => Course::where('status', 1)->latest()->get(),
        ]);
    }

    public function gallery()
    {
        $images = Course::where('status', 1)
            ->whereNotNull('image')
            ->pluck('image')
            ->map(fn ($image) => asset('storage/' . $image));

        return view('frontend.gallery', [
            'images' => $images,
        ]);
    }

    public function contact()
    {
        return view('frontend.contact', [
            'courses' => Course::where('status', 1)->get(),
        ]);
    }

    public function submitEnquiry(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|min:3|max:100',
            'mobile' => 'required|digits_between:10,15',
            'email' => 'nullable|email',
            'course_id' => 'nullable|exists:courses,id',
            'message' => 'nullable|max:2000',
        ]);

        $enquiry = Enquiry::create($data);

        if ($instituteEmail = Setting::get('institute_email')) {
            Notification::route('mail', $instituteEmail)
                ->notify(new EnquiryReceived($enquiry));
        }

        return back()->with('success', 'Thank you! We have received your enquiry and will contact you soon.');
    }
}
