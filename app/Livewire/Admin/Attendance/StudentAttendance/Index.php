<?php

namespace App\Livewire\Admin\Attendance\StudentAttendance;

use Livewire\Component;
use App\Models\Course;
use App\Models\Batch;
use App\Models\StudentRegistration;
use App\Models\StudentAttendance;

class Index extends Component
{
    public $attendance_date;

    public $course_id;

    public $batch_id;

    public $students = [];

    public $attendance_id = null;

    public function mount()
    {
        $this->attendance_date = now()->format('Y-m-d');
    }

    public function updatedCourseId()
    {
        $this->loadStudents();
    }

    public function updatedBatchId()
    {
        $this->loadStudents();
    }

    public function loadStudents()
    {
        if ($this->course_id && $this->batch_id) {

            $this->students = StudentRegistration::where('course_id', $this->course_id)
                ->where('batch_id', $this->batch_id)
                ->get()
                ->map(function ($student) {

                    return [

                        'student_id' => $student->id,

                        'name' => $student->name,

                        'status' => 'Present',

                    ];
                })->toArray();
        }
    }

    public function save()
    {
        $newlyAbsentIds = [];

        foreach ($this->students as $student) {

            // one row per student per day — re-saving updates instead of duplicating
            $record = StudentAttendance::updateOrCreate(

                [
                    'student_registration_id' => $student['student_id'],
                    'attendance_date' => $this->attendance_date,
                ],

                [
                    'course_id' => $this->course_id,
                    'batch_id' => $this->batch_id,
                    'status' => $student['status'],
                    'remarks' => null,
                ]

            );

            if ($record->status === 'Absent' && ($record->wasRecentlyCreated || $record->wasChanged('status'))) {
                $newlyAbsentIds[] = $student['student_id'];
            }
        }

        // alert the student and guardian by email (queued)
        foreach (StudentRegistration::whereIn('id', $newlyAbsentIds)->get() as $absentee) {
            foreach (array_filter([$absentee->email, $absentee->guardian_email]) as $email) {
                \Illuminate\Support\Facades\Notification::route('mail', $email)
                    ->notify(new \App\Notifications\StudentAbsent($absentee, $this->attendance_date));
            }
        }

        session()->flash(
            'success',
            $this->attendance_id
                ? 'Attendance Updated Successfully.'
                : 'Attendance Saved Successfully.'
        );

        $this->resetForm();
    }

    public function edit($id)
    {
        $attendance = StudentAttendance::findOrFail($id);

        $this->attendance_id = $attendance->id;

        $this->attendance_date = $attendance->attendance_date;

        $this->course_id = $attendance->course_id;

        $this->batch_id = $attendance->batch_id;

        $this->students = [

            [

                'student_id' => $attendance->student_registration_id,

                'name' => $attendance->student->name,

                'status' => $attendance->status,

            ]

        ];
    }

    public function delete($id)
    {
        StudentAttendance::findOrFail($id)->delete();

        session()->flash(
            'success',
            'Attendance Deleted Successfully.'
        );
    }

    public function resetForm()
    {
        $this->attendance_id = null;

        $this->attendance_date = now()->format('Y-m-d');

        $this->course_id = '';

        $this->batch_id = '';

        $this->students = [];
    }

    public function render()
    {
        return view(
            'livewire.admin.attendance.student-attendance.index',
            [

                'courses' => Course::where('status', 1)->get(),

                'batches' => Batch::where('status', 1)->get(),

                'attendanceList' => StudentAttendance::with([
                    'student',
                    'course',
                    'batch'
                ])
                    ->latest()
                    ->paginate(10),

            ]
        )->layout('layouts.admin');
    }
}
