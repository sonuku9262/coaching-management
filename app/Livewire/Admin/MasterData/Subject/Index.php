<?php

namespace App\Livewire\Admin\MasterData\Subject;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Subject;
use App\Models\Course;

class Index extends Component
{
    use WithPagination;

    public $search = '';

    public $subject_id;
    public $course_id;
    public $name;
    public $code;
    public $description;
    public $status = true;

    protected $rules = [
        'course_id' => 'required',
        'name' => 'required|min:2',
        'code' => 'required',
    ];

    public function save()
    {
        $this->validate();

        Subject::updateOrCreate(
            ['id' => $this->subject_id],
            [
                'course_id'   => $this->course_id,
                'name'        => $this->name,
                'code'        => $this->code,
                'description' => $this->description,
                'status'      => $this->status,
            ]
        );

        session()->flash(
            'success',
            $this->subject_id
                ? 'Subject Updated Successfully.'
                : 'Subject Added Successfully.'
        );

        $this->resetForm();
    }

    public function edit($id)
    {
        $subject = Subject::findOrFail($id);

        $this->subject_id = $subject->id;
        $this->course_id = $subject->course_id;
        $this->name = $subject->name;
        $this->code = $subject->code;
        $this->description = $subject->description;
        $this->status = $subject->status;
    }

    public function delete($id)
    {
        Subject::findOrFail($id)->delete();

        session()->flash(
            'success',
            'Subject Deleted Successfully.'
        );
    }

    public function resetForm()
    {
        $this->reset([
            'subject_id',
            'course_id',
            'name',
            'code',
            'description',
        ]);

        $this->status = true;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        return view(
            'livewire.admin.master-data.subject.index',
            [
                'subjects' => Subject::with('course')
                    ->where('name', 'like', '%' . $this->search . '%')
                    ->latest()
                    ->paginate(10),

                'courses' => Course::where('status', 1)->get(),
            ]
        )->layout('layouts.admin');
    }
}
