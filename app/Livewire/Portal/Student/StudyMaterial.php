<?php

namespace App\Livewire\Portal\Student;

use App\Models\StudentRegistration;
use App\Models\StudyMaterial as StudyMaterialModel;
use Livewire\Component;
use Livewire\WithPagination;

class StudyMaterial extends Component
{
    use WithPagination;

    public function render()
    {
        $student = StudentRegistration::where('user_id', auth()->id())->first();

        return view('livewire.portal.student.study-material', [
            'student' => $student,

            'materials' => $student
                ? StudyMaterialModel::with(['subject', 'teacher'])
                    ->where('batch_id', $student->batch_id)
                    ->where('status', 1)
                    ->latest()
                    ->paginate(10)
                : null,
        ])->layout('layouts.admin');
    }
}
