<?php

namespace App\Livewire\Admin\Examination\Exam;

use App\Models\Batch;
use App\Models\Course;
use App\Models\Exam;
use App\Models\ExamSchedule;
use App\Models\Subject;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';

    // exam form
    public $exam_id;
    public $name;
    public $course_id;
    public $batch_id;
    public $start_date;
    public $end_date;
    public $status = true;
    public $isEdit = false;

    // schedule form
    public $schedule_exam_id;
    public $subject_id;
    public $exam_date;
    public $start_time;
    public $end_time;
    public $total_marks = 100;
    public $passing_marks = 33;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function save()
    {
        abort_unless(auth()->user()->can($this->exam_id ? 'exams.edit' : 'exams.create'), 403);

        $this->validate([
            'name' => 'required|min:3',
            'course_id' => 'required|exists:courses,id',
            'batch_id' => 'required|exists:batches,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        Exam::updateOrCreate(
            ['id' => $this->exam_id],
            [
                'name' => $this->name,
                'course_id' => $this->course_id,
                'batch_id' => $this->batch_id,
                'start_date' => $this->start_date ?: null,
                'end_date' => $this->end_date ?: null,
                'status' => $this->status,
            ],
        );

        session()->flash('success', $this->exam_id ? 'Exam Updated Successfully.' : 'Exam Created Successfully.');

        $this->resetForm();
    }

    public function edit($id)
    {
        abort_unless(auth()->user()->can('exams.edit'), 403);

        $exam = Exam::findOrFail($id);

        $this->exam_id = $exam->id;
        $this->name = $exam->name;
        $this->course_id = $exam->course_id;
        $this->batch_id = $exam->batch_id;
        $this->start_date = $exam->start_date;
        $this->end_date = $exam->end_date;
        $this->status = $exam->status;
        $this->isEdit = true;
    }

    public function delete($id)
    {
        abort_unless(auth()->user()->can('exams.delete'), 403);

        Exam::findOrFail($id)->delete();

        session()->flash('success', 'Exam Deleted Successfully.');
    }

    public function manageSchedules($id)
    {
        abort_unless(auth()->user()->can('exams.edit'), 403);

        $this->schedule_exam_id = $id;
        $this->resetScheduleForm();
    }

    public function addSchedule()
    {
        abort_unless(auth()->user()->can('exams.edit'), 403);

        $this->validate([
            'subject_id' => 'required|exists:subjects,id|unique:exam_schedules,subject_id,NULL,id,exam_id,' . $this->schedule_exam_id,
            'exam_date' => 'required|date',
            'total_marks' => 'required|integer|min:1',
            'passing_marks' => 'required|integer|min:0|lte:total_marks',
        ], [
            'subject_id.unique' => 'This subject is already scheduled for the exam.',
        ]);

        ExamSchedule::create([
            'exam_id' => $this->schedule_exam_id,
            'subject_id' => $this->subject_id,
            'exam_date' => $this->exam_date,
            'start_time' => $this->start_time ?: null,
            'end_time' => $this->end_time ?: null,
            'total_marks' => $this->total_marks,
            'passing_marks' => $this->passing_marks,
        ]);

        session()->flash('success', 'Subject Scheduled Successfully.');

        $this->resetScheduleForm();
    }

    public function deleteSchedule($id)
    {
        abort_unless(auth()->user()->can('exams.edit'), 403);

        ExamSchedule::findOrFail($id)->delete();

        session()->flash('success', 'Schedule Removed.');
    }

    public function resetForm()
    {
        $this->reset(['exam_id', 'name', 'course_id', 'batch_id', 'start_date', 'end_date']);
        $this->status = true;
        $this->isEdit = false;
    }

    public function resetScheduleForm()
    {
        $this->reset(['subject_id', 'exam_date', 'start_time', 'end_time']);
        $this->total_marks = 100;
        $this->passing_marks = 33;
    }

    public function render()
    {
        $scheduleExam = $this->schedule_exam_id
            ? Exam::with(['schedules.subject', 'course', 'batch'])->find($this->schedule_exam_id)
            : null;

        return view('livewire.admin.examination.exam.index', [
            'exams' => Exam::with(['course', 'batch'])
                ->withCount('schedules')
                ->where('name', 'like', '%' . $this->search . '%')
                ->latest()
                ->paginate(10),
            'courses' => Course::where('status', 1)->get(),
            'batches' => $this->course_id
                ? Batch::where('status', 1)->where('course_id', $this->course_id)->get()
                : collect(),
            'scheduleExam' => $scheduleExam,
            'scheduleSubjects' => $scheduleExam
                ? Subject::where('status', 1)->where('course_id', $scheduleExam->course_id)->get()
                : collect(),
        ])->layout('layouts.admin');
    }
}
