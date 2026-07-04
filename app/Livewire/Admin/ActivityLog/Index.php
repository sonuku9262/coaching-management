<?php

namespace App\Livewire\Admin\ActivityLog;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Activitylog\Models\Activity;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $event = '';

    public function updating($property)
    {
        if (in_array($property, ['search', 'event'])) {
            $this->resetPage();
        }
    }

    public function render()
    {
        $activities = Activity::with('causer')
            ->when($this->event, fn ($q) => $q->where('event', $this->event))
            ->when($this->search, fn ($q) => $q->where(function ($query) {
                $query->where('description', 'like', '%' . $this->search . '%')
                    ->orWhere('subject_type', 'like', '%' . $this->search . '%');
            }))
            ->latest()
            ->paginate(20);

        return view('livewire.admin.activity-log.index', [
            'activities' => $activities,
        ])->layout('layouts.admin');
    }
}
