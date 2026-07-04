<?php

namespace App\Livewire\Admin\TeacherManagement\Teacher;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Teacher;
use App\Services\PortalAccountService;

class Index extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';

    public $teacher_id;

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
            ]
        )->layout('layouts.admin');
    }
}
