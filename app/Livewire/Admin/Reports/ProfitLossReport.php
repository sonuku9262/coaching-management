<?php

namespace App\Livewire\Admin\Reports;

use App\Models\Expense;
use App\Models\FeeCollection;
use App\Models\TeacherSalaryPayment;
use Livewire\Component;

class ProfitLossReport extends Component
{
    public $from_date;
    public $to_date;

    public function mount()
    {
        $this->from_date = now()->startOfMonth()->format('Y-m-d');
        $this->to_date = now()->format('Y-m-d');
    }

    protected function expenseByCategory()
    {
        return Expense::with('category')
            ->whereBetween('expense_date', [$this->from_date, $this->to_date])
            ->get()
            ->groupBy(fn ($expense) => $expense->category?->name ?? 'Uncategorized')
            ->map(fn ($rows) => $rows->sum('amount'));
    }

    public function export()
    {
        abort_unless(auth()->user()->can('reports.view'), 403);

        $income = (float) FeeCollection::whereBetween('payment_date', [$this->from_date, $this->to_date])->sum('paid_amount');
        $expenseByCategory = $this->expenseByCategory();
        $salary = (float) TeacherSalaryPayment::whereBetween('payment_date', [$this->from_date, $this->to_date])->sum('paid_amount');

        return response()->streamDownload(function () use ($income, $expenseByCategory, $salary) {
            $out = fopen('php://output', 'w');

            fputcsv($out, ['Profit & Loss Report', $this->from_date . ' to ' . $this->to_date]);
            fputcsv($out, []);
            fputcsv($out, ['Income', 'Amount']);
            fputcsv($out, ['Fee Collection', $income]);
            fputcsv($out, []);
            fputcsv($out, ['Expenses', 'Amount']);

            foreach ($expenseByCategory as $category => $amount) {
                fputcsv($out, [$category, $amount]);
            }

            fputcsv($out, ['Staff Salary', $salary]);
            fputcsv($out, []);
            fputcsv($out, ['Total Income', $income]);
            fputcsv($out, ['Total Expense', $expenseByCategory->sum() + $salary]);
            fputcsv($out, ['Net Profit / Loss', $income - ($expenseByCategory->sum() + $salary)]);

            fclose($out);
        }, 'profit-loss-' . $this->from_date . '-to-' . $this->to_date . '.csv');
    }

    public function render()
    {
        $income = (float) FeeCollection::whereBetween('payment_date', [$this->from_date, $this->to_date])->sum('paid_amount');
        $expenseByCategory = $this->expenseByCategory();
        $salary = (float) TeacherSalaryPayment::whereBetween('payment_date', [$this->from_date, $this->to_date])->sum('paid_amount');
        $totalExpense = $expenseByCategory->sum() + $salary;

        return view('livewire.admin.reports.profit-loss-report', [
            'income' => $income,
            'expenseByCategory' => $expenseByCategory,
            'salary' => $salary,
            'totalExpense' => $totalExpense,
            'netProfit' => $income - $totalExpense,
        ])->layout('layouts.admin');
    }
}
