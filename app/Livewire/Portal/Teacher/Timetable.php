<?php

namespace App\Livewire\Portal\Teacher;

use App\Models\ClassTimetable;
use App\Models\Teacher;
use Livewire\Component;

class Timetable extends Component
{
    protected array $dayOrder = [
        'Monday' => 1, 'Tuesday' => 2, 'Wednesday' => 3, 'Thursday' => 4,
        'Friday' => 5, 'Saturday' => 6, 'Sunday' => 7,
    ];

    public function render()
    {
        $teacher = Teacher::where('user_id', auth()->id())->first();

        $slots = collect();

        if ($teacher) {
            $slots = ClassTimetable::with(['batch', 'subject', 'classroom'])
                ->where('teacher_id', $teacher->id)
                ->get()
                ->sortBy(fn ($slot) => sprintf('%d-%s', $this->dayOrder[$slot->day_of_week] ?? 99, $slot->start_time))
                ->values();
        }

        return view('livewire.portal.teacher.timetable', [
            'teacher' => $teacher,
            'slots' => $slots,
        ])->layout('layouts.admin');
    }
}
