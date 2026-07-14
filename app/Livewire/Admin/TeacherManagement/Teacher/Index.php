<?php

namespace App\Livewire\Admin\TeacherManagement\Teacher;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Teacher;
use App\Models\TeacherBatchSubject;
use App\Models\Course;
use App\Models\Batch;
use App\Models\Subject;
use App\Services\PortalAccountService;

class Index extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';

    public $teacher_id;

    // Assign Subjects modal
    public $assign_teacher_id;
    public $assign_teacher_name;
    public $assign_course_id;
    public $assign_batch_id;
    public $assign_subject_id;

    public $employee_id;
    public $name;
    public $mobile;
    public $email;
    public $qualification;
    public $experience;
    public $photo;
    public $old_photo;
    public $address;
    public $joining_date;
    public $salary;
    public $status = true;

    protected $rules = [
        'employee_id' => 'required',
        'name' => 'required|min:3',
        'mobile' => 'required',
        'joining_date' => 'required',
        'salary' => 'required|numeric',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function createLogin($id, PortalAccountService $accounts)
    {
        abort_unless(auth()->user()->can('users.create'), 403);

        $teacher = Teacher::findOrFail($id);

        try {
            $accounts->createTeacherLogin($teacher);
            session()->flash('success', "Login created for {$teacher->name}. Default password is the mobile number.");
        } catch (\Illuminate\Validation\ValidationException $e) {
            session()->flash('error', collect($e->errors())->flatten()->first());
        }
    }

    public function save()
    {
        $this->validate();

        $photoName = $this->old_photo;

        if ($this->photo) {
            $photoName = $this->photo->store('teachers', 'public');
        }

        Teacher::updateOrCreate(

            ['id' => $this->teacher_id],

            [

                'employee_id'   => $this->employee_id,
                'name'          => $this->name,
                'mobile'        => $this->mobile,
                'email'         => $this->email,
                'qualification' => $this->qualification,
                'experience'    => $this->experience,
                'photo'         => $photoName,
                'address'       => $this->address,
                'joining_date'  => $this->joining_date,
                'salary'        => $this->salary,
                'status'        => $this->status,

            ]

        );

        session()->flash(
            'success',
            $this->teacher_id
                ? 'Teacher Updated Successfully.'
                : 'Teacher Added Successfully.'
        );

        $this->resetForm();
    }

    public function edit($id)
    {
        $teacher = Teacher::findOrFail($id);

        $this->teacher_id     = $teacher->id;
        $this->employee_id    = $teacher->employee_id;
        $this->name           = $teacher->name;
        $this->mobile         = $teacher->mobile;
        $this->email          = $teacher->email;
        $this->qualification  = $teacher->qualification;
        $this->experience     = $teacher->experience;
        $this->old_photo      = $teacher->photo;
        $this->address        = $teacher->address;
        $this->joining_date   = $teacher->joining_date;
        $this->salary         = $teacher->salary;
        $this->status         = $teacher->status;
    }

    public function delete($id)
    {
        Teacher::findOrFail($id)->delete();

        session()->flash(
            'success',
            'Teacher Deleted Successfully.'
        );
    }

    public function updatedAssignCourseId()
    {
        $this->assign_batch_id = '';
        $this->assign_subject_id = '';
    }

    public function manageAssignments($id)
    {
        abort_unless(auth()->user()->can('teachers.edit'), 403);

        $teacher = Teacher::findOrFail($id);

        $this->assign_teacher_id = $teacher->id;
        $this->assign_teacher_name = $teacher->name;
        $this->assign_course_id = '';
        $this->assign_batch_id = '';
        $this->assign_subject_id = '';
    }

    public function addAssignment()
    {
        abort_unless(auth()->user()->can('teachers.edit'), 403);

        $this->validate([
            'assign_batch_id' => 'required|exists:batches,id',
            'assign_subject_id' => 'required|exists:subjects,id',
        ]);

        TeacherBatchSubject::firstOrCreate([
            'teacher_id' => $this->assign_teacher_id,
            'batch_id' => $this->assign_batch_id,
            'subject_id' => $this->assign_subject_id,
        ]);

        $this->assign_subject_id = '';

        session()->flash('success', 'Subject Assigned Successfully.');
    }

    public function removeAssignment($id)
    {
        abort_unless(auth()->user()->can('teachers.edit'), 403);

        TeacherBatchSubject::findOrFail($id)->delete();

        session()->flash('success', 'Assignment Removed Successfully.');
    }

    public function resetForm()
    {
        $this->reset([
            'teacher_id',
            'employee_id',
            'name',
            'mobile',
            'email',
            'qualification',
            'experience',
            'photo',
            'old_photo',
            'address',
            'joining_date',
            'salary',
        ]);

        $this->status = true;
    }

    public function render()
    {
        return view(
            'livewire.admin.teacher-management.teacher.index',
            [
                'teachers' => Teacher::where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('employee_id', 'like', '%' . $this->search . '%')
                    ->latest()
                    ->paginate(10),

                'courses' => Course::where('status', 1)->get(),

                'assignBatches' => $this->assign_course_id
                    ? Batch::where('course_id', $this->assign_course_id)->where('status', 1)->get()
                    : collect(),

                'assignSubjects' => $this->assign_course_id
                    ? Subject::where('course_id', $this->assign_course_id)->where('status', 1)->get()
                    : collect(),

                'currentAssignments' => $this->assign_teacher_id
                    ? TeacherBatchSubject::with(['batch', 'subject'])
                        ->where('teacher_id', $this->assign_teacher_id)
                        ->latest()
                        ->get()
                    : collect(),
            ]
        )->layout('layouts.admin');
    }
}
