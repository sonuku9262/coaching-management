<?php

namespace App\Livewire\Portal\Teacher;

use App\Models\StudentRegistration;
use App\Models\Teacher;
use Livewire\Component;

class MyBatches extends Component
{
    public function render()
    {
        $teacher = Teacher::where('user_id', auth()->id())->first();

        $assignments = collect();

        if ($teacher) {
            $assignments = $teacher->assignments()
                ->with(['batch.course', 'subject'])
                ->get()
                ->map(function ($assignment) {
                    return [
                        'assignment' => $assignment,
                        'studentCount' => StudentRegistration::where('batch_id', $assignment->batch_id)
                            ->where('status', 1)
                            ->count(),
                    ];
                });
        }

        return view('livewire.portal.teacher.my-batches', [
            'teacher' => $teacher,
            'assignments' => $assignments,
        ])->layout('layouts.admin');
    }
}
