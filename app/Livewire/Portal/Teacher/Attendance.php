<?php

namespace App\Livewire\Portal\Teacher;

use App\Models\Batch;
use App\Models\StudentAttendance;
use App\Models\StudentRegistration;
use App\Models\Teacher;
use Livewire\Component;

class Attendance extends Component
{
    public $attendance_date;

    public $batch_id;

    public $students = [];

    public function mount()
    {
        $this->attendance_date = now()->format('Y-m-d');

        $allowedBatchIds = $this->myBatchIds();

        $requestedBatchId = (int) request('batch_id');

        if ($requestedBatchId && in_array($requestedBatchId, $allowedBatchIds)) {
            $this->batch_id = $requestedBatchId;
            $this->loadStudents();
        }
    }

    protected function teacher(): ?Teacher
    {
        return Teacher::where('user_id', auth()->id())->first();
    }

    protected function myBatchIds(): array
    {
        $teacher = $this->teacher();

        return $teacher ? $teacher->assignments()->pluck('batch_id')->unique()->all() : [];
    }

    public function updatedBatchId()
    {
        $this->loadStudents();
    }

    public function loadStudents()
    {
        $this->students = [];

        if (! $this->batch_id || ! in_array((int) $this->batch_id, $this->myBatchIds())) {
            return;
        }

        $this->students = StudentRegistration::where('batch_id', $this->batch_id)
            ->where('status', 1)
            ->orderBy('name')
            ->get()
            ->map(function ($student) {
                return [
                    'student_id' => $student->id,
                    'name' => $student->name,
                    'status' => 'Present',
                ];
            })->toArray();
    }

    public function save()
    {
        abort_unless(in_array((int) $this->batch_id, $this->myBatchIds()), 403);

        $batch = Batch::findOrFail($this->batch_id);

        $newlyAbsentIds = [];

        foreach ($this->students as $student) {
            $record = StudentAttendance::updateOrCreate(
                [
                    'student_registration_id' => $student['student_id'],
                    'attendance_date' => $this->attendance_date,
                ],
                [
                    'course_id' => $batch->course_id,
                    'batch_id' => $batch->id,
                    'status' => $student['status'],
                    'remarks' => null,
                ]
            );

            if ($record->status === 'Absent' && ($record->wasRecentlyCreated || $record->wasChanged('status'))) {
                $newlyAbsentIds[] = $student['student_id'];
            }
        }

        foreach (StudentRegistration::whereIn('id', $newlyAbsentIds)->get() as $absentee) {
            foreach (array_filter([$absentee->email, $absentee->guardian_email]) as $email) {
                \Illuminate\Support\Facades\Notification::route('mail', $email)
                    ->notify(new \App\Notifications\StudentAbsent($absentee, $this->attendance_date));
            }
        }

        session()->flash('success', 'Attendance Saved Successfully.');
    }

    public function render()
    {
        $allowedBatchIds = $this->myBatchIds();

        return view('livewire.portal.teacher.attendance', [
            'batches' => Batch::whereIn('id', $allowedBatchIds)->get(),

            'attendanceList' => StudentAttendance::with(['student', 'batch'])
                ->whereIn('batch_id', $allowedBatchIds)
                ->latest()
                ->paginate(10),
        ])->layout('layouts.admin');
    }
}
