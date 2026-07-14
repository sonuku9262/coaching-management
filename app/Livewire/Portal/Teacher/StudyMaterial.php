<?php

namespace App\Livewire\Portal\Teacher;

use App\Models\Batch;
use App\Models\StudyMaterial as StudyMaterialModel;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class StudyMaterial extends Component
{
    use WithFileUploads;

    public $study_material_id;

    public $batch_id;
    public $subject_id;
    public $title;
    public $description;
    public $file;
    public $old_file_path;
    public $old_file_name;

    protected $rules = [
        'batch_id' => 'required',
        'subject_id' => 'required',
        'title' => 'required|min:3|max:150',
        'file' => 'nullable|file|max:10240',
    ];

    protected function teacher(): ?Teacher
    {
        return Teacher::where('user_id', auth()->id())->first();
    }

    /** @return \Illuminate\Support\Collection<int, \App\Models\TeacherBatchSubject> */
    protected function myAssignments()
    {
        $teacher = $this->teacher();

        return $teacher ? $teacher->assignments()->get(['batch_id', 'subject_id']) : collect();
    }

    protected function myBatchIds(): array
    {
        return $this->myAssignments()->pluck('batch_id')->unique()->all();
    }

    protected function mySubjectIdsForBatch(?int $batchId): array
    {
        if (! $batchId) {
            return [];
        }

        return $this->myAssignments()->where('batch_id', $batchId)->pluck('subject_id')->unique()->all();
    }

    protected function scopeToMyAssignments($query)
    {
        $assignments = $this->myAssignments();

        if ($assignments->isEmpty()) {
            return $query->whereRaw('1 = 0');
        }

        return $query->where(function ($q) use ($assignments) {
            foreach ($assignments as $assignment) {
                $q->orWhere(function ($q2) use ($assignment) {
                    $q2->where('batch_id', $assignment->batch_id)
                        ->where('subject_id', $assignment->subject_id);
                });
            }
        });
    }

    public function updatedBatchId()
    {
        $this->subject_id = '';
    }

    protected function assignmentAllowed(): bool
    {
        return in_array((int) $this->batch_id, $this->myBatchIds())
            && in_array((int) $this->subject_id, $this->mySubjectIdsForBatch((int) $this->batch_id));
    }

    public function save()
    {
        $this->validate();

        abort_unless($this->assignmentAllowed(), 403);

        $filePath = $this->old_file_path;
        $fileName = $this->old_file_name;

        if ($this->file) {
            $filePath = $this->file->store('study-materials', 'public');
            $fileName = $this->file->getClientOriginalName();
        }

        StudyMaterialModel::updateOrCreate(
            ['id' => $this->study_material_id],
            [
                'batch_id' => $this->batch_id,
                'subject_id' => $this->subject_id,
                'teacher_id' => $this->teacher()?->id,
                'title' => $this->title,
                'description' => $this->description,
                'file_path' => $filePath,
                'file_name' => $fileName,
                'status' => true,
            ]
        );

        session()->flash(
            'success',
            $this->study_material_id ? 'Study Material Updated Successfully.' : 'Study Material Uploaded Successfully.'
        );

        $this->resetForm();
    }

    public function edit($id)
    {
        $material = StudyMaterialModel::findOrFail($id);

        abort_unless(
            in_array($material->subject_id, $this->mySubjectIdsForBatch($material->batch_id)),
            403
        );

        $this->study_material_id = $material->id;
        $this->batch_id = $material->batch_id;
        $this->subject_id = $material->subject_id;
        $this->title = $material->title;
        $this->description = $material->description;
        $this->old_file_path = $material->file_path;
        $this->old_file_name = $material->file_name;
        $this->file = null;
    }

    public function delete($id)
    {
        $material = StudyMaterialModel::findOrFail($id);

        abort_unless(
            in_array($material->subject_id, $this->mySubjectIdsForBatch($material->batch_id)),
            403
        );

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
    }

    public function render()
    {
        return view('livewire.portal.teacher.study-material', [
            'batches' => Batch::whereIn('id', $this->myBatchIds())->get(),

            'subjects' => $this->batch_id
                ? Subject::whereIn('id', $this->mySubjectIdsForBatch((int) $this->batch_id))->get()
                : collect(),

            'materials' => $this->scopeToMyAssignments(
                StudyMaterialModel::with(['batch', 'subject'])->latest()
            )->paginate(10),
        ])->layout('layouts.admin');
    }
}
