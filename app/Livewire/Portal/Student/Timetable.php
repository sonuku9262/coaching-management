<?php

namespace App\Livewire\Portal\Student;

use App\Models\ClassTimetable;
use App\Models\StudentRegistration;
use Livewire\Component;

class Timetable extends Component
{
    protected array $dayOrder = [
        'Monday' => 1, 'Tuesday' => 2, 'Wednesday' => 3, 'Thursday' => 4,
        'Friday' => 5, 'Saturday' => 6, 'Sunday' => 7,
    ];

    public function render()
    {
        $student = StudentRegistration::where('user_id', auth()->id())->first();

        $slots = collect();

        if ($student) {
            $slots = ClassTimetable::with(['subject', 'teacher', 'classroom'])
                ->where('batch_id', $student->batch_id)
                ->get()
                ->sortBy(fn ($slot) => sprintf('%d-%s', $this->dayOrder[$slot->day_of_week] ?? 99, $slot->start_time))
                ->values();
        }

        return view('livewire.portal.student.timetable', [
            'student' => $student,
            'slots' => $slots,
        ])->layout('layouts.admin');
    }
}
