<?php

namespace App\Livewire\Admin\MasterData\Batch;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Batch;
use App\Models\Course;
use App\Models\AcademicYear;
use App\Models\AcademicSession;

class Index extends Component
{
    use WithPagination;

    public $search = '';

    public $batch_id;
    public $academic_year_id;
    public $academic_session_id;
    public $course_id;
    public $name;
    public $start_date;
    public $end_date;
    public $capacity;
    public $status = true;

    protected $rules = [
        'academic_year_id' => 'required',
        'academic_session_id' => 'required',
        'course_id' => 'required',
        'name' => 'required',
        'start_date' => 'required',
        'end_date' => 'required',
        'capacity' => 'required|numeric|min:1',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function save()
    {
        $this->validate();

        Batch::updateOrCreate(
            ['id' => $this->batch_id],
            [
                'academic_year_id' => $this->academic_year_id,
                'academic_session_id' => $this->academic_session_id,
                'course_id' => $this->course_id,
                'name' => $this->name,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'capacity' => $this->capacity,
                'status' => $this->status,
            ]
        );

        session()->flash(
            'success',
            $this->batch_id
                ? 'Batch Updated Successfully.'
                : 'Batch Added Successfully.'
        );

        $this->resetForm();
    }

    public function edit($id)
    {
        $batch = Batch::findOrFail($id);

        $this->batch_id = $batch->id;
        $this->academic_year_id = $batch->academic_year_id;
        $this->academic_session_id = $batch->academic_session_id;
        $this->course_id = $batch->course_id;
        $this->name = $batch->name;
        $this->start_date = $batch->start_date;
        $this->end_date = $batch->end_date;
        $this->capacity = $batch->capacity;
        $this->status = $batch->status;
    }

    public function delete($id)
    {
        Batch::findOrFail($id)->delete();

        session()->flash('success', 'Batch Deleted Successfully.');
    }

    public function resetForm()
    {
        $this->reset([
            'batch_id',
            'academic_year_id',
            'academic_session_id',
            'course_id',
            'name',
            'start_date',
            'end_date',
            'capacity',
        ]);

        $this->status = true;
    }

    public function render()
    {
        return view('livewire.admin.master-data.batch.index', [
            'batches' => Batch::with(['academicYear', 'academicSession', 'course'])
                ->where('name', 'like', '%' . $this->search . '%')
                ->latest()
                ->paginate(10),

            'academicYears' => AcademicYear::where('status', 1)->get(),
            'academicSessions' => AcademicSession::where('status', 1)->get(),
            'courses' => Course::where('status', 1)->get(),
        ])->layout('layouts.admin');
    }
}
