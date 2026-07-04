<?php

namespace App\Livewire\Admin\Examination\Result;

use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\ExamSchedule;
use App\Models\StudentRegistration;
use Livewire\Component;

class Index extends Component
{
    public $exam_id;
    public $schedule_id;

    /** @var array<int, array{student_id:int, admission_no:string, name:string, marks:mixed, is_absent:bool}> */
    public $rows = [];

    public function updatedExamId()
    {
        $this->schedule_id = null;
        $this->rows = [];
    }

    public function updatedScheduleId()
    {
        $this->loadRows();
    }

    public function loadRows()
    {
        $this->rows = [];

        if (! $this->schedule_id) {
            return;
        }

        $schedule = ExamSchedule::with('exam')->findOrFail($this->schedule_id);

        $existing = ExamResult::where('exam_schedule_id', $schedule->id)
            ->get()
            ->keyBy('student_registration_id');

        $this->rows = StudentRegistration::where('course_id', $schedule->exam->course_id)
            ->where('batch_id', $schedule->exam->batch_id)
            ->where('status', 1)
            ->orderBy('name')
            ->get()
            ->map(function ($student) use ($existing) {
                $result = $existing->get($student->id);

                return [
                    'student_id' => $student->id,
                    'admission_no' => $student->admission_no,
                    'name' => $student->name,
                    'marks' => $result?->marks_obtained,
                    'is_absent' => (bool) ($result?->is_absent ?? false),
                ];
            })->toArray();
    }

    public function save()
    {
        abort_unless(auth()->user()->can('exam-results.create') || auth()->user()->can('exam-results.edit'), 403);

        $schedule = ExamSchedule::findOrFail($this->schedule_id);

        $this->validate([
            'rows.*.marks' => 'nullable|numeric|min:0|max:' . $schedule->total_marks,
        ], [
            'rows.*.marks.max' => 'Marks cannot exceed total marks (' . $schedule->total_marks . ').',
        ]);

        foreach ($this->rows as $row) {
            $isAbsent = (bool) $row['is_absent'];

            ExamResult::updateOrCreate(
                [
                    'exam_schedule_id' => $schedule->id,
                    'student_registration_id' => $row['student_id'],
                ],
                [
                    'marks_obtained' => $isAbsent ? null : ($row['marks'] !== '' ? $row['marks'] : null),
                    'is_absent' => $isAbsent,
                ],
            );
        }

        session()->flash('success', 'Results Saved Successfully.');
    }

    public function render()
    {
        $exam = $this->exam_id ? Exam::find($this->exam_id) : null;
        $schedule = $this->schedule_id ? ExamSchedule::with('subject')->find($this->schedule_id) : null;

        return view('livewire.admin.examination.result.index', [
            'exams' => Exam::with(['course', 'batch'])->where('status', 1)->latest()->get(),
            'schedules' => $exam
                ? $exam->schedules()->with('subject')->orderBy('exam_date')->get()
                : collect(),
            'schedule' => $schedule,
        ])->layout('layouts.admin');
    }
}
