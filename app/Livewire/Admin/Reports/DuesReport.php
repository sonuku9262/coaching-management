<?php

namespace App\Livewire\Admin\Reports;

use App\Models\Course;
use App\Models\StudentRegistration;
use Livewire\Component;
use Livewire\WithPagination;

class DuesReport extends Component
{
    use WithPagination;

    public $search = '';
    public $course_id = '';

    public function updating($property)
    {
        if (in_array($property, ['search', 'course_id'])) {
            $this->resetPage();
        }
    }

    protected function query()
    {
        return StudentRegistration::with(['course', 'batch'])
            ->where('status', 1)
            ->when($this->course_id, fn ($q) => $q->where('course_id', $this->course_id))
            ->when($this->search, fn ($q) => $q->where(function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('admission_no', 'like', '%' . $this->search . '%');
            }))
            ->withSum('feeCollections as total_paid', 'paid_amount')
            ->withSum('feeCollections as outstanding_balance', 'balance')
            // correlated subquery instead of HAVING so it works on MySQL and SQLite alike
            ->whereRaw('(select coalesce(sum(balance), 0) from fee_collections where fee_collections.student_registration_id = student_registrations.id) > 0')
            ->orderByDesc('outstanding_balance');
    }

    public function export()
    {
        abort_unless(auth()->user()->can('reports.view'), 403);

        $rows = $this->query()->get();

        return response()->streamDownload(function () use ($rows) {
            $out = fopen('php://output', 'w');

            fputcsv($out, ['Admission No', 'Student', 'Mobile', 'Course', 'Batch', 'Total Paid', 'Outstanding Balance']);

            foreach ($rows as $student) {
                fputcsv($out, [
                    $student->admission_no,
                    $student->name,
                    $student->mobile,
                    $student->course?->name,
                    $student->batch?->name,
                    $student->total_paid ?? 0,
                    $student->outstanding_balance,
                ]);
            }

            fclose($out);
        }, 'dues-report-' . now()->format('Y-m-d') . '.csv');
    }

    public function render()
    {
        $totalDue = (clone $this->query())->get()->sum('outstanding_balance');

        return view('livewire.admin.reports.dues-report', [
            'students' => $this->query()->paginate(15),
            'totalDue' => $totalDue,
            'courses' => Course::where('status', 1)->get(),
        ])->layout('layouts.admin');
    }
}
