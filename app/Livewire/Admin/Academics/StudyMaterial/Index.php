<?php

namespace App\Livewire\Admin\Academics\StudyMaterial;

use App\Models\Batch;
use App\Models\StudyMaterial;
use App\Models\Subject;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';

    public $study_material_id;

    public $batch_id;
    public $subject_id;
    public $title;
    public $description;
    public $file;
    public $old_file_path;
    public $old_file_name;
    public $status = true;

    protected $rules = [
        'batch_id' => 'required|exists:batches,id',
        'subject_id' => 'required|exists:subjects,id',
        'title' => 'required|min:3|max:150',
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
            $filePath = $this->file->store('study-materials', 'public');
            $fileName = $this->file->getClientOriginalName();
        }

        StudyMaterial::updateOrCreate(
            ['id' => $this->study_material_id],
            [
                'batch_id' => $this->batch_id,
                'subject_id' => $this->subject_id,
                'title' => $this->title,
                'description' => $this->description,
                'file_path' => $filePath,
                'file_name' => $fileName,
                'status' => $this->status,
            ]
        );

        session()->flash(
            'success',
            $this->study_material_id ? 'Study Material Updated Successfully.' : 'Study Material Added Successfully.'
        );

        $this->resetForm();
    }

    public function edit($id)
    {
        $material = StudyMaterial::findOrFail($id);

        $this->study_material_id = $material->id;
        $this->batch_id = $material->batch_id;
        $this->subject_id = $material->subject_id;
        $this->title = $material->title;
        $this->description = $material->description;
        $this->old_file_path = $material->file_path;
        $this->old_file_name = $material->file_name;
        $this->status = $material->status;
        $this->file = null;
    }

    public function delete($id)
    {
        $material = StudyMaterial::findOrFail($id);

        if ($material->file_path) {
            Storage::disk('public')->delete($material->file_path);
        }

        $material->delete();

        session()->flash('success', 'Study Material Deleted Successfully.');
    }

    public function resetForm()
    {
        $this->reset([
            'study_material_id', 'batch_id', 'subject_id', 'title',
            'description', 'file', 'old_file_path', 'old_file_name',
        ]);

        $this->status = true;
    }

    public function render()
    {
        return view(
            'livewire.admin.academics.study-material.index',
            [
                'materials' => StudyMaterial::with(['batch', 'subject', 'teacher'])
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
