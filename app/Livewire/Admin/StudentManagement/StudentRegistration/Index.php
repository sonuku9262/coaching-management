<?php

namespace App\Livewire\Admin\StudentManagement\StudentRegistration;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\StudentRegistration;
use App\Models\AcademicYear;
use App\Models\AcademicSession;
use App\Models\Course;
use App\Models\Batch;
use App\Models\Classroom;
use App\Models\Shift;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class Index extends Component
{

    use WithPagination, WithFileUploads;

    public $search = '';

    public $student_id;

    public $admission_no;

    public $academic_year_id;
    public $academic_session_id;
    public $course_id;
    public $batch_id;
    public $classroom_id;
    public $shift_id;

    public $name;
    public $father_name;
    public $mother_name;

    public $gender = 'Male';

    public $dob;

    public $mobile;

    public $email;

    public $address;

    public $admission_date;

    public $photo;

    public $status = true;



    protected $rules = [

        'academic_year_id' => 'required',
        'academic_session_id' => 'required',
        'course_id' => 'required',
        'batch_id' => 'required',
        'classroom_id' => 'required',
        'shift_id' => 'required',

        'name' => 'required|min:3',

        'father_name' => 'required',

        'mobile' => 'required',

        'dob' => 'required',

        'admission_date' => 'required',

    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function save()
    {
        $this->validate();

        if (!$this->student_id) {
            $lastStudent = StudentRegistration::latest()->first();

            $nextId = $lastStudent ? $lastStudent->id + 1 : 1;

            $this->admission_no = 'ADM-' . date('Y') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
        }

        $photoPath = null;

        if ($this->photo) {
            $photoPath = $this->photo->store('students', 'public');
        }

        StudentRegistration::updateOrCreate(

            ['id' => $this->student_id],

            [

                'admission_no' => $this->admission_no,

                'academic_year_id' => $this->academic_year_id,

                'academic_session_id' => $this->academic_session_id,

                'course_id' => $this->course_id,

                'batch_id' => $this->batch_id,

                'classroom_id' => $this->classroom_id,

                'shift_id' => $this->shift_id,

                'name' => $this->name,

                'father_name' => $this->father_name,

                'mother_name' => $this->mother_name,

                'gender' => $this->gender,

                'dob' => $this->dob,

                'mobile' => $this->mobile,

                'email' => $this->email,

                'address' => $this->address,

                'admission_date' => $this->admission_date,

                'photo' => $photoPath,

                'status' => $this->status,

            ]

        );

        session()->flash(
            'success',
            $this->student_id
                ? 'Student Updated Successfully.'
                : 'Student Added Successfully.'
        );

        $this->resetForm();
    }

    public function edit($id)
    {
        $student = StudentRegistration::findOrFail($id);

        $this->student_id = $student->id;

        $this->admission_no = $student->admission_no;

        $this->academic_year_id = $student->academic_year_id;

        $this->academic_session_id = $student->academic_session_id;

        $this->course_id = $student->course_id;

        $this->batch_id = $student->batch_id;

        $this->classroom_id = $student->classroom_id;

        $this->shift_id = $student->shift_id;

        $this->name = $student->name;

        $this->father_name = $student->father_name;

        $this->mother_name = $student->mother_name;

        $this->gender = $student->gender;

        $this->dob = $student->dob;

        $this->mobile = $student->mobile;

        $this->email = $student->email;

        $this->address = $student->address;

        $this->admission_date = $student->admission_date;

        $this->status = $student->status;
    }

    public function delete($id)
    {
        $student = StudentRegistration::findOrFail($id);

        if ($student->photo) {

            Storage::disk('public')->delete($student->photo);
        }

        $student->delete();

        session()->flash(
            'success',
            'Student Deleted Successfully.'
        );
    }

    public function resetForm()
    {
        $this->reset([

            'student_id',

            'admission_no',

            'academic_year_id',

            'academic_session_id',

            'course_id',

            'batch_id',

            'classroom_id',

            'shift_id',

            'name',

            'father_name',

            'mother_name',

            'gender',

            'dob',

            'mobile',

            'email',

            'address',

            'admission_date',

            'photo',

        ]);

        $this->status = true;

        $this->gender = 'Male';
    }

    public function render()
    {
        return view(
            'livewire.admin.student-management.student-registration.index',
            [

                'students' => StudentRegistration::with([
                    'academicYear',
                    'academicSession',
                    'course',
                    'batch',
                    'classroom',
                    'shift',
                ])
                    ->where('name', 'like', '%' . $this->search . '%')
                    ->latest()
                    ->paginate(10),

                'academicYears' => AcademicYear::where('status', 1)->get(),

                'academicSessions' => AcademicSession::where('status', 1)->get(),

                'courses' => Course::where('status', 1)->get(),

                'batches' => Batch::where('status', 1)->get(),

                'classrooms' => Classroom::where('status', 1)->get(),

                'shifts' => Shift::where('status', 1)->get(),

            ]

        )->layout('layouts.admin');
    }
}
