<?php

namespace App\Livewire\Admin\Course;

use App\Models\Course;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';

    public $course_id;
    public $name;
    public $code;
    public $duration;
    public $duration_type = 'Months';
    public $fees;
    public $description;
    public $status = true;


    protected $rules = [
        'name' => 'required|min:3|max:100',
        'code' => 'required|unique:courses,code',
        'duration' => 'required|integer|min:1',
        'fees' => 'required|numeric|min:0',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function save()
    {
        $this->validate();

        Course::updateOrCreate(

            ['id' => $this->course_id],

            [
                'name' => $this->name,
                'code' => $this->code,
                'duration' => $this->duration,
                'duration_type' => $this->duration_type,
                'fees' => $this->fees,
                'description' => $this->description,
                'status' => $this->status,
            ]

        );

        session()->flash(
            'success',
            $this->course_id
                ? 'Course Updated Successfully.'
                : 'Course Added Successfully.'
        );

        $this->resetForm();
    }

    public function edit($id)
    {
        $course = Course::findOrFail($id);

        $this->course_id = $course->id;
        $this->name = $course->name;
        $this->code = $course->code;
        $this->duration = $course->duration;
        $this->duration_type = $course->duration_type;
        $this->fees = $course->fees;
        $this->description = $course->description;
        $this->status = $course->status;
    }

    public function resetForm()
    {
        $this->reset([
            'course_id',
            'name',
            'code',
            'duration',
            'fees',
            'description',
        ]);

        $this->duration_type = 'Months';
        $this->status = true;
    }

    public function delete($id)
    {
        Course::findOrFail($id)->delete();

        session()->flash(
            'success',
            'Course Deleted Successfully.'
        );
    }





    public function render()
    {
        $courses = Course::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('code', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate(10);

        return view('livewire.admin.course.index', [
            'courses' => $courses,
        ])->layout('layouts.admin');
    }
}
