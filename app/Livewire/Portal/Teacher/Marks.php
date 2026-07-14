<?php

namespace App\Livewire\Portal\Teacher;

use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\ExamSchedule;
use App\Models\StudentRegistration;
use App\Models\Teacher;
use Livewire\Component;

class Marks extends Component
{
    public $exam_id;
    public $schedule_id;

    /** @var array<int, array{student_id:int, admission_no:string, name:string, marks:mixed, is_absent:bool}> */
    public $rows = [];

    public function mount()
    {
        $requestedBatchId = (int) request('batch_id');

        if ($requestedBatchId && in_array($requestedBatchId, $this->myBatchIds())) {
            $exam = Exam::where('batch_id', $requestedBatchId)->where('status', true)->latest()->first();

            if ($exam) {
                $this->exam_id = $exam->id;
            }
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

    protected function mySubjectIdsForBatch(?int $batchId): array
    {
        $teacher = $this->teacher();

        if (! $teacher || ! $batchId) {
            return [];
        }

        return $teacher->assignments()->where('batch_id', $batchId)->pluck('subject_id')->unique()->all();
    }

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

        $allowed = in_array($schedule->exam->batch_id, $this->myBatchIds())
            && in_array($schedule->subject_id, $this->mySubjectIdsForBatch($schedule->exam->batch_id));

        if (! $allowed) {
            return;
        }

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
        $schedule = ExamSchedule::with('exam')->findOrFail($this->schedule_id);

        $allowed = in_array($schedule->exam->batch_id, $this->myBatchIds())
            && in_array($schedule->subject_id, $this->mySubjectIdsForBatch($schedule->exam->batch_id));

        abort_unless($allowed, 403);

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
        $batchIds = $this->myBatchIds();

        $exam = $this->exam_id ? Exam::find($this->exam_id) : null;

        $schedules = collect();

        if ($exam) {
            $allowedSubjectIds = $this->mySubjectIdsForBatch($exam->batch_id);

            $schedules = $exam->schedules()
                ->with('subject')
                ->whereIn('subject_id', $allowedSubjectIds)
                ->orderBy('exam_date')
                ->get();
        }

        return view('livewire.portal.teacher.marks', [
            'exams' => Exam::with(['course', 'batch'])
                ->whereIn('batch_id', $batchIds)
                ->where('status', true)
                ->latest()
                ->get(),
            'schedules' => $schedules,
            'schedule' => $this->schedule_id ? ExamSchedule::with('subject')->find($this->schedule_id) : null,
        ])->layout('layouts.admin');
    }
}
