<?php

namespace App\Livewire\Admin\MasterData\Shift;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Shift;

class Index extends Component
{
    use WithPagination;

    public $search = '';

    public $shift_id;
    public $name;
    public $start_time;
    public $end_time;
    public $status = true;

    protected $rules = [
        'name' => 'required|min:2|max:100',
        'start_time' => 'required',
        'end_time' => 'required',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function save()
    {
        $this->validate();

        Shift::updateOrCreate(
            ['id' => $this->shift_id],
            [
                'name' => $this->name,
                'start_time' => $this->start_time,
                'end_time' => $this->end_time,
                'status' => $this->status,
            ]
        );

        session()->flash(
            'success',
            $this->shift_id
                ? 'Shift Updated Successfully.'
                : 'Shift Added Successfully.'
        );

        $this->resetForm();
    }

    public function edit($id)
    {
        $shift = Shift::findOrFail($id);

        $this->shift_id = $shift->id;
        $this->name = $shift->name;
        $this->start_time = $shift->start_time;
        $this->end_time = $shift->end_time;
        $this->status = $shift->status;
    }

    public function delete($id)
    {
        Shift::findOrFail($id)->delete();

        session()->flash('success', 'Shift Deleted Successfully.');
    }

    public function resetForm()
    {
        $this->reset([
            'shift_id',
            'name',
            'start_time',
            'end_time',
        ]);

        $this->status = true;
    }

    public function render()
    {
        return view('livewire.admin.master-data.shift.index', [
            'shifts' => Shift::where('name', 'like', '%' . $this->search . '%')
                ->latest()
                ->paginate(10),
        ])->layout('layouts.admin');
    }
}