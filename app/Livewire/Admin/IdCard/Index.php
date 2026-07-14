<?php

namespace App\Livewire\Admin\IdCard;

use App\Models\Batch;
use App\Models\Course;
use App\Models\StudentRegistration;
use Livewire\Component;

class Index extends Component
{
    public $course_id;
    public $batch_id;

    public function updatedCourseId()
    {
        $this->batch_id = null;
    }

    public function render()
    {
        $students = collect();

        if ($this->batch_id) {
            $students = StudentRegistration::with(['course', 'batch'])
                ->where('batch_id', $this->batch_id)
                ->where('status', 1)
                ->orderBy('name')
                ->get();
        }

        return view('livewire.admin.id-card.index', [
            'courses' => Course::where('status', 1)->get(),

            'batches' => $this->course_id
                ? Batch::where('course_id', $this->course_id)->where('status', 1)->get()
                : collect(),

            'students' => $students,
        ])->layout('layouts.admin');
    }
}
