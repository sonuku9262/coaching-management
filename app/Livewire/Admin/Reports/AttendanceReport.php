<?php

namespace App\Livewire\Admin\Reports;

use App\Models\Batch;
use App\Models\Course;
use App\Models\StudentRegistration;
use Livewire\Component;

class AttendanceReport extends Component
{
    public $from_date;
    public $to_date;
    public $course_id = '';
    public $batch_id = '';

    public function mount()
    {
        $this->from_date = now()->startOfMonth()->format('Y-m-d');
        $this->to_date = now()->format('Y-m-d');
    }

    protected function rows()
    {
        if (! $this->course_id) {
            return collect();
        }

        return StudentRegistration::where('course_id', $this->course_id)
            ->when($this->batch_id, fn ($q) => $q->where('batch_id', $this->batch_id))
            ->where('status', 1)
            ->orderBy('name')
            ->withCount([
                'attendances as present_count' => fn ($q) => $q->where('status', 'Present')
                    ->whereBetween('attendance_date', [$this->from_date, $this->to_date]),
                'attendances as absent_count' => fn ($q) => $q->where('status', 'Absent')
                    ->whereBetween('attendance_date', [$this->from_date, $this->to_date]),
                'attendances as leave_count' => fn ($q) => $q->where('status', 'Leave')
                    ->whereBetween('attendance_date', [$this->from_date, $this->to_date]),
                'attendances as total_count' => fn ($q) => $q
                    ->whereBetween('attendance_date', [$this->from_date, $this->to_date]),
            ])
            ->get();
    }

    public function export()
    {
        abort_unless(auth()->user()->can('reports.view'), 403);

        $rows = $this->rows();

        return response()->streamDownload(function () use ($rows) {
            $out = fopen('php://output', 'w');

            fputcsv($out, ['Admission No', 'Student', 'Present', 'Absent', 'Leave', 'Marked Days', 'Attendance %']);

            foreach ($rows as $student) {
                fputcsv($out, [
                    $student->admission_no,
                    $student->name,
                    $student->present_count,
                    $student->absent_count,
                    $student->leave_count,
                    $student->total_count,
                    $student->total_count ? round($student->present_count / $student->total_count * 100) . '%' : '-',
                ]);
            }

            fclose($out);
        }, 'attendance-report-' . $this->from_date . '-to-' . $this->to_date . '.csv');
    }

    public function render()
    {
        return view('livewire.admin.reports.attendance-report', [
            'rows' => $this->rows(),
            'courses' => Course::where('status', 1)->get(),
            'batches' => $this->course_id
                ? Batch::where('status', 1)->where('course_id', $this->course_id)->get()
                : collect(),
        ])->layout('layouts.admin');
    }
}
