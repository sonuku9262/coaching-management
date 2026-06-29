<?php

namespace App\Livewire\Admin\MasterData\AcademicYear;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\AcademicYear;

class Index extends Component
{
    use WithPagination;

    public $search = '';

    public $academic_year_id;

    public $name;

    public $start_date;

    public $end_date;

    public $status = true;

    protected $rules = [

        'name' => 'required|max:100',

        'start_date' => 'required|date',

        'end_date' => 'required|date|after:start_date',

    ];

    public function save()
    {
        $this->validate();

        AcademicYear::updateOrCreate(

            ['id' => $this->academic_year_id],

            [

                'name' => $this->name,

                'start_date' => $this->start_date,

                'end_date' => $this->end_date,

                'status' => $this->status,

            ]

        );

        session()->flash(
            'success',
            $this->academic_year_id
                ? 'Academic Year Updated Successfully.'
                : 'Academic Year Added Successfully.'
        );

        $this->resetForm();
    }

    public function edit($id)
    {
        $year = AcademicYear::findOrFail($id);

        $this->academic_year_id = $year->id;

        $this->name = $year->name;

        $this->start_date = $year->start_date;

        $this->end_date = $year->end_date;

        $this->status = $year->status;
    }

    public function delete($id)
    {
        AcademicYear::findOrFail($id)->delete();

        session()->flash(
            'success',
            'Academic Year Deleted Successfully.'
        );
    }

    public function resetForm()
    {
        $this->reset([
            'academic_year_id',
            'name',
            'start_date',
            'end_date',
        ]);

        $this->status = true;
    }

    public function render()
    {
        $academicYears = AcademicYear::where('name', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate(10);

        return view(
            'livewire.admin.master-data.academic-year.index',
            compact('academicYears')
        )->layout('layouts.admin');
    }
}
