<?php

namespace App\Livewire\Admin\Academics\Homework;

use App\Models\Batch;
use App\Models\Homework;
use App\Models\Subject;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';

    public $homework_id;

    public $batch_id;
    public $subject_id;
    public $title;
    public $description;
    public $due_date;
    public $file;
    public $old_file_path;
    public $old_file_name;
    public $status = true;

    protected $rules = [
        'batch_id' => 'required|exists:batches,id',
        'subject_id' => 'required|exists:subjects,id',
        'title' => 'required|min:3|max:150',
        'due_date' => 'required|date',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedBatchId()
    {
        $this->subject_id = '';
    }

    public function save()
    {
        $this->validate([
            ...$this->rules,
            'file' => 'nullable|file|max:10240',
        ]);

        $filePath = $this->old_file_path;
        $fileName = $this->old_file_name;

        if ($this->file) {
            $filePath = $this->file->store('homework', 'public');
            $fileName = $this->file->getClientOriginalName();
        }

        Homework::updateOrCreate(
            ['id' => $this->homework_id],
            [
                'batch_id' => $this->batch_id,
                'subject_id' => $this->subject_id,
                'title' => $this->title,
                'description' => $this->description,
                'due_date' => $this->due_date,
                'file_path' => $filePath,
                'file_name' => $fileName,
                'status' => $this->status,
            ]
        );

        session()->flash(
            'success',
            $this->homework_id ? 'Homework Updated Successfully.' : 'Homework Assigned Successfully.'
        );

        $this->resetForm();
    }

    public function edit($id)
    {
        $homework = Homework::findOrFail($id);

        $this->homework_id = $homework->id;
        $this->batch_id = $homework->batch_id;
        $this->subject_id = $homework->subject_id;
        $this->title = $homework->title;
        $this->description = $homework->description;
        $this->due_date = $homework->due_date;
        $this->old_file_path = $homework->file_path;
        $this->old_file_name = $homework->file_name;
        $this->status = $homework->status;
        $this->file = null;
    }

    public function delete($id)
    {
        $homework = Homework::findOrFail($id);

        if ($homework->file_path) {
            Storage::disk('public')->delete($homework->file_path);
        }

        $homework->delete();

        session()->flash('success', 'Homework Deleted Successfully.');
    }

    public function resetForm()
    {
        $this->reset([
            'homework_id', 'batch_id', 'subject_id', 'title',
            'description', 'due_date', 'file', 'old_file_path', 'old_file_name',
        ]);

        $this->status = true;
    }

    public function render()
    {
        return view(
            'livewire.admin.academics.homework.index',
            [
                'homeworks' => Homework::with(['batch', 'subject', 'teacher'])
                    ->when($this->search, fn ($q) => $q->where('title', 'like', '%' . $this->search . '%'))
                    ->latest()
                    ->paginate(10),

                'batches' => Batch::where('status', 1)->get(),

                'subjects' => $this->batch_id
                    ? Subject::where('course_id', Batch::find($this->batch_id)?->course_id)->where('status', 1)->get()
                    : collect(),
            ]
        )->layout('layouts.admin');
    }
}
