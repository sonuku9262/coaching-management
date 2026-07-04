<?php

namespace App\Livewire\Admin\Examination;

use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\StudentRegistration;
use Livewire\Component;

class ReportCard extends Component
{
    public $exam_id;
    public $student_id;

    public function updatedExamId()
    {
        $this->student_id = null;
    }

    public function render()
    {
        $exam = $this->exam_id
            ? Exam::with(['course', 'batch', 'schedules.subject'])->find($this->exam_id)
            : null;

        $students = $exam
            ? StudentRegistration::where('course_id', $exam->course_id)
                ->where('batch_id', $exam->batch_id)
                ->where('status', 1)
                ->orderBy('name')
                ->get()
            : collect();

        $student = $this->student_id ? StudentRegistration::find($this->student_id) : null;

        $rows = collect();
        $summary = null;

        if ($exam && $student) {
            $results = ExamResult::where('student_registration_id', $student->id)
                ->whereIn('exam_schedule_id', $exam->schedules->pluck('id'))
                ->get()
                ->keyBy('exam_schedule_id');

            $rows = $exam->schedules->map(function ($schedule) use ($results) {
                $result = $results->get($schedule->id);

                return [
                    'subject' => $schedule->subject?->name,
                    'total' => $schedule->total_marks,
                    'passing' => $schedule->passing_marks,
                    'marks' => $result?->is_absent ? null : $result?->marks_obtained,
                    'absent' => (bool) ($result?->is_absent ?? false),
                    'entered' => $result !== null,
                    'pass' => $result && ! $result->is_absent && $result->marks_obtained !== null
                        ? $result->marks_obtained >= $schedule->passing_marks
                        : false,
                ];
            });

            $entered = $rows->where('entered', true);

            $summary = [
                'obtained' => $entered->sum(fn ($r) => (float) ($r['marks'] ?? 0)),
                'total' => $entered->sum('total'),
                'percent' => $entered->sum('total') > 0
                    ? round($entered->sum(fn ($r) => (float) ($r['marks'] ?? 0)) / $entered->sum('total') * 100, 2)
                    : null,
                'result' => $entered->isNotEmpty() && $entered->every(fn ($r) => $r['pass']) ? 'PASS' : 'FAIL',
            ];
        }

        return view('livewire.admin.examination.report-card', [
            'exams' => Exam::with(['course', 'batch'])->latest()->get(),
            'exam' => $exam,
            'students' => $students,
            'student' => $student,
            'rows' => $rows,
            'summary' => $summary,
        ])->layout('layouts.admin');
    }
}
