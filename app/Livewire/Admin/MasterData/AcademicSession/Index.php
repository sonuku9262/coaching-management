<?php

namespace App\Livewire\Admin\MasterData\AcademicSession;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\AcademicSession;
use App\Models\AcademicYear;

class Index extends Component
{
    use WithPagination;

    public $search='';

    public $academic_session_id;
    public $academic_year_id;
    public $name;
    public $start_date;
    public $end_date;
    public $status=true;

    protected $rules=[
        'academic_year_id'=>'required',
        'name'=>'required',
        'start_date'=>'required',
        'end_date'=>'required',
    ];

    public function save()
    {
        $this->validate();

        AcademicSession::updateOrCreate(
            ['id'=>$this->academic_session_id],
            [
                'academic_year_id'=>$this->academic_year_id,
                'name'=>$this->name,
                'start_date'=>$this->start_date,
                'end_date'=>$this->end_date,
                'status'=>$this->status,
            ]
        );

        session()->flash(
            'success',
            $this->academic_session_id
                ? 'Academic Session Updated Successfully.'
                : 'Academic Session Added Successfully.'
        );

        $this->resetForm();
    }

    public function edit($id)
    {
        $session=AcademicSession::findOrFail($id);

        $this->academic_session_id=$session->id;
        $this->academic_year_id=$session->academic_year_id;
        $this->name=$session->name;
        $this->start_date=$session->start_date;
        $this->end_date=$session->end_date;
        $this->status=$session->status;
    }

    public function delete($id)
    {
        AcademicSession::findOrFail($id)->delete();

        session()->flash(
            'success',
            'Academic Session Deleted Successfully.'
        );
    }

    public function resetForm()
    {
        $this->reset([
            'academic_session_id',
            'academic_year_id',
            'name',
            'start_date',
            'end_date',
        ]);

        $this->status=true;
    }

    public function render()
    {
        return view(
            'livewire.admin.master-data.academic-session.index',
            [
                'sessions'=>AcademicSession::with('academicYear')
                    ->where('name','like','%'.$this->search.'%')
                    ->latest()
                    ->paginate(10),

                'academicYears'=>AcademicYear::where('status',1)->get(),
            ]
        )->layout('layouts.admin');
    }
}