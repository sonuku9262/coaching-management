<?php

namespace App\Livewire\Portal\ParentPortal;

use App\Livewire\Portal\ParentPortal\Concerns\ResolvesChild;
use Livewire\Component;

class Results extends Component
{
    use ResolvesChild;

    public function render()
    {
        $children = $this->children();
        $student = $this->resolveChild($children);

        if ($student) {
            $this->student_id = $student->id;
        }

        $exams = collect();

        if ($student) {
            $results = $student->examResults()
                ->with(['schedule.exam', 'schedule.subject'])
                ->get()
                ->filter(fn ($result) => $result->schedule?->exam)
                ->groupBy(fn ($result) => $result->schedule->exam_id);

            $exams = $results->map(function ($examResults) {
                $exam = $examResults->first()->schedule->exam;

                $rows = $examResults->map(fn ($result) => [
                    'subject' => $result->schedule->subject?->name,
                    'total' => $result->schedule->total_marks,
                    'passing' => $result->schedule->passing_marks,
                    'marks' => $result->is_absent ? null : $result->marks_obtained,
                    'absent' => $result->is_absent,
                    'pass' => ! $result->is_absent
                        && $result->marks_obtained !== null
                        && $result->marks_obtained >= $result->schedule->passing_marks,
                ]);

                $totalMarks = $rows->sum('total');
                $obtained = $rows->sum(fn ($row) => (float) ($row['marks'] ?? 0));

                return [
                    'exam' => $exam,
                    'rows' => $rows,
                    'obtained' => $obtained,
                    'total' => $totalMarks,
                    'percent' => $totalMarks > 0 ? round($obtained / $totalMarks * 100, 2) : null,
                    'result' => $rows->every(fn ($row) => $row['pass']) ? 'PASS' : 'FAIL',
                ];
            })->sortByDesc(fn ($exam) => $exam['exam']->created_at)->values();
        }

        return view('livewire.portal.parent.results', [
            'children' => $children,
            'student' => $student,
            'exams' => $exams,
        ])->layout('layouts.admin');
    }
}
