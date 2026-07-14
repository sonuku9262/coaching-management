<?php

namespace App\Livewire\Admin\Examination;

use App\Models\Exam;
use App\Models\StudentRegistration;
use Livewire\Component;

class AdmitCard extends Component
{
    public $exam_id;

    public function render()
    {
        $exam = $this->exam_id
            ? Exam::with(['course', 'batch', 'schedules' => fn ($q) => $q->orderBy('exam_date')->with('subject')])
                ->find($this->exam_id)
            : null;

        $students = $exam
            ? StudentRegistration::where('course_id', $exam->course_id)
                ->where('batch_id', $exam->batch_id)
                ->where('status', 1)
                ->orderBy('name')
                ->get()
            : collect();

        return view('livewire.admin.examination.admit-card', [
            'exams' => Exam::with(['course', 'batch'])->latest()->get(),
            'exam' => $exam,
            'students' => $students,
        ])->layout('layouts.admin');
    }
}
