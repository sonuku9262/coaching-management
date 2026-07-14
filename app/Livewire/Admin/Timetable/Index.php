<?php

namespace App\Livewire\Admin\Timetable;

use App\Models\Batch;
use App\Models\Classroom;
use App\Models\ClassTimetable;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\TeacherBatchSubject;
use Livewire\Component;

class Index extends Component
{
    protected array $dayOrder = [
        'Monday' => 1, 'Tuesday' => 2, 'Wednesday' => 3, 'Thursday' => 4,
        'Friday' => 5, 'Saturday' => 6, 'Sunday' => 7,
    ];

    public $batch_id = '';

    public $timetable_id;

    public $subject_id;
    public $teacher_id;
    public $classroom_id;
    public $day_of_week;
    public $start_time;
    public $end_time;

    protected $rules = [
        'batch_id' => 'required|exists:batches,id',
        'subject_id' => 'required|exists:subjects,id',
        'teacher_id' => 'nullable|exists:teachers,id',
        'classroom_id' => 'nullable|exists:classrooms,id',
        'day_of_week' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
        'start_time' => 'required',
        'end_time' => 'required|after:start_time',
    ];

    public function updatedBatchId()
    {
        $this->subject_id = '';
        $this->teacher_id = '';
    }

    public function resetForm()
    {
        $this->reset([
            'timetable_id', 'subject_id', 'teacher_id',
            'classroom_id', 'day_of_week', 'start_time', 'end_time',
        ]);
    }

    public function save()
    {
        abort_unless(auth()->user()->can('timetables.create') || auth()->user()->can('timetables.edit'), 403);

        $this->validate();

        $clash = ClassTimetable::where('batch_id', $this->batch_id)
            ->where('day_of_week', $this->day_of_week)
            ->when($this->timetable_id, fn ($q) => $q->where('id', '!=', $this->timetable_id))
            ->where('start_time', '<', $this->end_time)
            ->where('end_time', '>', $this->start_time)
            ->exists();

        if ($clash) {
            $this->addError('start_time', 'This batch already has a class scheduled in this time range.');

            return;
        }

        ClassTimetable::updateOrCreate(
            ['id' => $this->timetable_id],
            [
                'batch_id' => $this->batch_id,
                'subject_id' => $this->subject_id,
                'teacher_id' => $this->teacher_id ?: null,
                'classroom_id' => $this->classroom_id ?: null,
                'day_of_week' => $this->day_of_week,
                'start_time' => $this->start_time,
                'end_time' => $this->end_time,
            ]
        );

        session()->flash(
            'success',
            $this->timetable_id ? 'Timetable Slot Updated Successfully.' : 'Timetable Slot Added Successfully.'
        );

        $this->resetForm();
    }

    public function edit($id)
    {
        $slot = ClassTimetable::findOrFail($id);

        $this->timetable_id = $slot->id;
        $this->batch_id = $slot->batch_id;
        $this->subject_id = $slot->subject_id;
        $this->teacher_id = $slot->teacher_id;
        $this->classroom_id = $slot->classroom_id;
        $this->day_of_week = $slot->day_of_week;
        $this->start_time = substr($slot->start_time, 0, 5);
        $this->end_time = substr($slot->end_time, 0, 5);
    }

    public function delete($id)
    {
        ClassTimetable::findOrFail($id)->delete();

        session()->flash('success', 'Timetable Slot Deleted Successfully.');
    }

    public function render()
    {
        $slots = collect();

        if ($this->batch_id) {
            $slots = ClassTimetable::with(['subject', 'teacher', 'classroom'])
                ->where('batch_id', $this->batch_id)
                ->get()
                ->sortBy(fn ($slot) => sprintf('%d-%s', $this->dayOrder[$slot->day_of_week] ?? 99, $slot->start_time))
                ->values();
        }

        $subjects = collect();
        $eligibleTeachers = collect();

        if ($this->batch_id) {
            $courseId = Batch::find($this->batch_id)?->course_id;

            $subjects = Subject::where('course_id', $courseId)->where('status', 1)->get();

            if ($this->subject_id) {
                $eligibleTeachers = Teacher::whereIn('id', TeacherBatchSubject::where('batch_id', $this->batch_id)
                    ->where('subject_id', $this->subject_id)
                    ->pluck('teacher_id'))
                    ->get();
            }
        }

        return view('livewire.admin.timetable.index', [
            'batches' => Batch::where('status', 1)->get(),
            'subjects' => $subjects,
            'teachers' => $eligibleTeachers,
            'classrooms' => Classroom::where('status', 1)->get(),
            'slots' => $slots,
        ])->layout('layouts.admin');
    }
}
