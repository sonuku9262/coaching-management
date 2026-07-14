<?php

namespace App\Livewire\Portal\Student;

use App\Models\Homework as HomeworkModel;
use App\Models\StudentRegistration;
use Livewire\Component;
use Livewire\WithPagination;

class Homework extends Component
{
    use WithPagination;

    public function render()
    {
        $student = StudentRegistration::where('user_id', auth()->id())->first();

        return view('livewire.portal.student.homework', [
            'student' => $student,

            'homeworks' => $student
                ? HomeworkModel::with(['subject', 'teacher'])
                    ->where('batch_id', $student->batch_id)
                    ->where('status', 1)
                    ->orderByDesc('due_date')
                    ->paginate(10)
                : null,
        ])->layout('layouts.admin');
    }
}
