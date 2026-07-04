<?php

namespace App\Livewire\Admin\Reports;

use App\Models\Course;
use App\Models\FeeCollection;
use Livewire\Component;
use Livewire\WithPagination;

class FeeReport extends Component
{
    use WithPagination;

    public $from_date;
    public $to_date;
    public $course_id = '';
    public $payment_mode = '';

    public function mount()
    {
        $this->from_date = now()->startOfMonth()->format('Y-m-d');
        $this->to_date = now()->format('Y-m-d');
    }

    public function updating($property)
    {
        if (in_array($property, ['from_date', 'to_date', 'course_id', 'payment_mode'])) {
            $this->resetPage();
        }
    }

    protected function query()
    {
        return FeeCollection::with(['student.course', 'feeType'])
            ->whereBetween('payment_date', [$this->from_date, $this->to_date])
            ->when($this->course_id, function ($query) {
                $query->whereHas('student', fn ($q) => $q->where('course_id', $this->course_id));
            })
            ->when($this->payment_mode, fn ($query) => $query->where('payment_mode', $this->payment_mode))
            ->orderByDesc('payment_date');
    }

    public function export()
    {
        abort_unless(auth()->user()->can('reports.view'), 403);

        $rows = $this->query()->get();

        return response()->streamDownload(function () use ($rows) {
            $out = fopen('php://output', 'w');

            fputcsv($out, ['Receipt No', 'Date', 'Student', 'Admission No', 'Course', 'Fee Type', 'Amount', 'Discount', 'Fine', 'Paid', 'Balance', 'Mode']);

            foreach ($rows as $fee) {
                fputcsv($out, [
                    $fee->receipt_no,
                    $fee->payment_date,
                    $fee->student?->name,
                    $fee->student?->admission_no,
                    $fee->student?->course?->name,
                    $fee->feeType?->name,
                    $fee->amount,
                    $fee->discount,
                    $fee->fine,
                    $fee->paid_amount,
                    $fee->balance,
                    $fee->payment_mode,
                ]);
            }

            fclose($out);
        }, 'fee-report-' . $this->from_date . '-to-' . $this->to_date . '.csv');
    }

    public function render()
    {
        $totals = [
            'paid' => (clone $this->query())->sum('paid_amount'),
            'discount' => (clone $this->query())->sum('discount'),
            'fine' => (clone $this->query())->sum('fine'),
            'balance' => (clone $this->query())->sum('balance'),
        ];

        return view('livewire.admin.reports.fee-report', [
            'collections' => $this->query()->paginate(15),
            'totals' => $totals,
            'courses' => Course::where('status', 1)->get(),
            'paymentModes' => FeeCollection::whereNotNull('payment_mode')->distinct()->pluck('payment_mode'),
        ])->layout('layouts.admin');
    }
}
