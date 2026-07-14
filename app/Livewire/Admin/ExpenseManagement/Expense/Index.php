<?php

namespace App\Livewire\Admin\ExpenseManagement\Expense;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Expense;
use App\Models\ExpenseCategory;

class Index extends Component
{
    use WithPagination;

    public $search = '';

    public $expense_id;

    public $expense_category_id;
    public $amount;
    public $expense_date;
    public $payment_mode = 'Cash';
    public $paid_to;
    public $voucher_no;
    public $remarks;
    public $status = true;

    protected $rules = [
        'expense_category_id' => 'required|exists:expense_categories,id',
        'amount' => 'required|numeric|min:0.01',
        'expense_date' => 'required',
    ];

    public function mount()
    {
        $this->expense_date = now()->format('Y-m-d');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function save()
    {
        $this->validate();

        if (! $this->expense_id) {
            $next = (Expense::max('id') ?? 0) + 1;

            $this->voucher_no = 'EXP-' . date('Y') . '-' . str_pad($next, 5, '0', STR_PAD_LEFT);
        }

        Expense::updateOrCreate(
            ['id' => $this->expense_id],
            [
                'expense_category_id' => $this->expense_category_id,
                'amount' => $this->amount,
                'expense_date' => $this->expense_date,
                'payment_mode' => $this->payment_mode,
                'paid_to' => $this->paid_to,
                'voucher_no' => $this->voucher_no,
                'remarks' => $this->remarks,
                'status' => $this->status,
            ]
        );

        session()->flash(
            'success',
            $this->expense_id ? 'Expense Updated Successfully.' : 'Expense Recorded Successfully.'
        );

        $this->resetForm();
    }

    public function edit($id)
    {
        $expense = Expense::findOrFail($id);

        $this->expense_id = $expense->id;
        $this->expense_category_id = $expense->expense_category_id;
        $this->amount = $expense->amount;
        $this->expense_date = $expense->expense_date;
        $this->payment_mode = $expense->payment_mode;
        $this->paid_to = $expense->paid_to;
        $this->voucher_no = $expense->voucher_no;
        $this->remarks = $expense->remarks;
        $this->status = $expense->status;
    }

    public function delete($id)
    {
        Expense::findOrFail($id)->delete();

        session()->flash('success', 'Expense Deleted Successfully.');
    }

    public function resetForm()
    {
        $this->reset([
            'expense_id', 'expense_category_id', 'amount',
            'paid_to', 'voucher_no', 'remarks',
        ]);

        $this->expense_date = now()->format('Y-m-d');
        $this->payment_mode = 'Cash';
        $this->status = true;
    }

    public function render()
    {
        return view(
            'livewire.admin.expense-management.expense.index',
            [
                'expenses' => Expense::with('category')
                    ->when($this->search, fn ($q) => $q->where('paid_to', 'like', '%' . $this->search . '%')
                        ->orWhere('voucher_no', 'like', '%' . $this->search . '%'))
                    ->latest('expense_date')
                    ->paginate(10),

                'categories' => ExpenseCategory::where('status', 1)->get(),
            ]
        )->layout('layouts.admin');
    }
}
