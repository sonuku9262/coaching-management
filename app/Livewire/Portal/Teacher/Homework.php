<?php

namespace App\Livewire\Portal\Teacher;

use App\Models\Batch;
use App\Models\Homework as HomeworkModel;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Homework extends Component
{
    use WithFileUploads;

    public $homework_id;

    public $batch_id;
    public $subject_id;
    public $title;
    public $description;
    public $due_date;
    public $file;
    public $old_file_path;
    public $old_file_name;

    protected $rules = [
        'batch_id' => 'required',
        'subject_id' => 'required',
        'title' => 'required|min:3|max:150',
        'due_date' => 'required|date',
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
            $filePath = $this->file->store('homework', 'public');
            $fileName = $this->file->getClientOriginalName();
        }

        HomeworkModel::updateOrCreate(
            ['id' => $this->homework_id],
            [
                'batch_id' => $this->batch_id,
                'subject_id' => $this->subject_id,
                'teacher_id' => $this->teacher()?->id,
                'title' => $this->title,
                'description' => $this->description,
                'due_date' => $this->due_date,
                'file_path' => $filePath,
                'file_name' => $fileName,
                'status' => true,
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
        $homework = HomeworkModel::findOrFail($id);

        abort_unless(
            in_array($homework->subject_id, $this->mySubjectIdsForBatch($homework->batch_id)),
            403
        );

        $this->homework_id = $homework->id;
        $this->batch_id = $homework->batch_id;
        $this->subject_id = $homework->subject_id;
        $this->title = $homework->title;
        $this->description = $homework->description;
        $this->due_date = $homework->due_date;
        $this->old_file_path = $homework->file_path;
        $this->old_file_name = $homework->file_name;
        $this->file = null;
    }

    public function delete($id)
    {
        $homework = HomeworkModel::findOrFail($id);

        abort_unless(
            in_array($homework->subject_id, $this->mySubjectIdsForBatch($homework->batch_id)),
            403
        );

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
    }

    public function render()
    {
        return view('livewire.portal.teacher.homework', [
            'batches' => Batch::whereIn('id', $this->myBatchIds())->get(),

            'subjects' => $this->batch_id
                ? Subject::whereIn('id', $this->mySubjectIdsForBatch((int) $this->batch_id))->get()
                : collect(),

            'homeworks' => $this->scopeToMyAssignments(
                HomeworkModel::with(['batch', 'subject'])->latest()
            )->paginate(10),
        ])->layout('layouts.admin');
    }
}
