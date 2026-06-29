<?php

namespace App\Livewire\Admin\MasterData\Classroom;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Classroom;

class Index extends Component
{
    use WithPagination;

    public $search = '';

    public $classroom_id;
    public $name;
    public $room_no;
    public $floor;
    public $capacity;
    public $status = true;

    protected $rules = [
        'name' => 'required',
        'room_no' => 'required',
        'capacity' => 'required|numeric|min:1',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function save()
    {
        $this->validate();

        Classroom::updateOrCreate(
            ['id' => $this->classroom_id],
            [
                'name' => $this->name,
                'room_no' => $this->room_no,
                'floor' => $this->floor,
                'capacity' => $this->capacity,
                'status' => $this->status,
            ]
        );

        session()->flash(
            'success',
            $this->classroom_id
                ? 'Classroom Updated Successfully.'
                : 'Classroom Added Successfully.'
        );

        $this->resetForm();
    }

    public function edit($id)
    {
        $classroom = Classroom::findOrFail($id);

        $this->classroom_id = $classroom->id;
        $this->name = $classroom->name;
        $this->room_no = $classroom->room_no;
        $this->floor = $classroom->floor;
        $this->capacity = $classroom->capacity;
        $this->status = $classroom->status;
    }

    public function delete($id)
    {
        Classroom::findOrFail($id)->delete();

        session()->flash(
            'success',
            'Classroom Deleted Successfully.'
        );
    }

    public function resetForm()
    {
        $this->reset([
            'classroom_id',
            'name',
            'room_no',
            'floor',
            'capacity',
        ]);

        $this->status = true;
    }

    public function render()
    {
        return view(
            'livewire.admin.master-data.classroom.index',
            [
                'classrooms' => Classroom::where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('room_no', 'like', '%' . $this->search . '%')
                    ->latest()
                    ->paginate(10),
            ]
        )->layout('layouts.admin');
    }
}
