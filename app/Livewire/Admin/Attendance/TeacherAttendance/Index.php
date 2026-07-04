<?php

namespace App\Livewire\Admin\Attendance\TeacherAttendance;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Teacher;
use App\Models\TeacherAttendance;

class Index extends Component
{
    use WithPagination;

    public $attendance_id;

    public $attendance_date;

    public $teachers = [];

    public function mount()
    {
        $this->attendance_date = now()->format('Y-m-d');

        $this->loadTeachers();
    }

    public function loadTeachers()
    {
        $this->teachers = Teacher::where('status', 1)
            ->get()
            ->map(function ($teacher) {

                return [

                    'teacher_id' => $teacher->id,

                    'name' => $teacher->name,

                    'status' => 'Present',

                ];
            })->toArray();
    }

    public function save()
    {
        foreach ($this->teachers as $teacher) {

            TeacherAttendance::updateOrCreate(

                [

                    'teacher_id' => $teacher['teacher_id'],

                    'attendance_date' => $this->attendance_date,

                ],

                [

                    'status' => $teacher['status'],

                    'remarks' => null,

                ]

            );
        }

        session()->flash(
            'success',
            'Teacher Attendance Saved Successfully.'
        );

        $this->resetForm();
    }

    public function edit($id)
    {
        $attendance = TeacherAttendance::with('teacher')->findOrFail($id);

        $this->attendance_id = $attendance->id;

        $this->attendance_date = $attendance->attendance_date;

        $this->teachers = [

            [

                'teacher_id' => $attendance->teacher_id,

                'name'       => $attendance->teacher->name,

                'status'     => $attendance->status,

            ]

        ];
    }

    public function delete($id)
    {
        TeacherAttendance::findOrFail($id)->delete();

        session()->flash(
            'success',
            'Teacher Attendance Deleted Successfully.'
        );
    }

    public function resetForm()
    {
        $this->attendance_id = null;

        $this->attendance_date = now()->format('Y-m-d');

        $this->loadTeachers();
    }

    public function render()
    {
        return view(
            'livewire.admin.attendance.teacher-attendance.index',
            [

                'attendanceList' => TeacherAttendance::with('teacher')
                    ->latest()
                    ->paginate(10),

            ]
        )->layout('layouts.admin');
    }
}
