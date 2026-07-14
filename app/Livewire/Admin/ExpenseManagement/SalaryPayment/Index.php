<?php

namespace App\Livewire\Admin\ExpenseManagement\SalaryPayment;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Teacher;
use App\Models\TeacherSalaryPayment;

class Index extends Component
{
    use WithPagination;

    public $search = '';

    public $salary_payment_id;

    public $teacher_id;
    public $salary_month;
    public $amount = 0;
    public $deduction = 0;
    public $paid_amount = 0;
    public $payment_date;
    public $payment_mode = 'Cash';
    public $remarks;

    protected $rules = [
        'teacher_id' => 'required|exists:teachers,id',
        'salary_month' => 'required',
        'amount' => 'required|numeric|min:0',
        'payment_date' => 'required',
    ];

    public function mount()
    {
        $this->payment_date = now()->format('Y-m-d');
        $this->salary_month = now()->format('Y-m');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedTeacherId()
    {
        $teacher = Teacher::find($this->teacher_id);

        if ($teacher) {
            $this->amount = $teacher->salary;
            $this->deduction = 0;
            $this->paid_amount = $teacher->salary;
        }
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['amount', 'deduction'])) {
            $amount = (float) ($this->amount ?: 0);
            $deduction = (float) ($this->deduction ?: 0);

            $this->paid_amount = max(0, $amount - $deduction);
        }
    }

    public function save()
    {
        $this->validate();

        $monthDate = $this->salary_month . '-01';

        $clash = TeacherSalaryPayment::where('teacher_id', $this->teacher_id)
            ->where('salary_month', $monthDate)
            ->when($this->salary_payment_id, fn ($q) => $q->where('id', '!=', $this->salary_payment_id))
            ->exists();

        if ($clash) {
            $this->addError('salary_month', 'Salary for this teacher and month is already recorded.');

            return;
        }

        if ($this->salary_payment_id) {
            $voucherNo = TeacherSalaryPayment::find($this->salary_payment_id)->voucher_no;
        } else {
            $next = (TeacherSalaryPayment::max('id') ?? 0) + 1;

            $voucherNo = 'SAL-' . date('Y') . '-' . str_pad($next, 5, '0', STR_PAD_LEFT);
        }

        TeacherSalaryPayment::updateOrCreate(
            ['id' => $this->salary_payment_id],
            [
                'teacher_id' => $this->teacher_id,
                'salary_month' => $monthDate,
                'amount' => $this->amount,
                'deduction' => $this->deduction,
                'paid_amount' => $this->paid_amount,
                'payment_date' => $this->payment_date,
                'payment_mode' => $this->payment_mode,
                'voucher_no' => $voucherNo,
                'remarks' => $this->remarks,
            ]
        );

        session()->flash(
            'success',
            $this->salary_payment_id ? 'Salary Payment Updated Successfully.' : 'Salary Payment Recorded Successfully.'
        );

        $this->resetForm();
    }

    public function edit($id)
    {
        $payment = TeacherSalaryPayment::findOrFail($id);

        $this->salary_payment_id = $payment->id;
        $this->teacher_id = $payment->teacher_id;
        $this->salary_month = substr($payment->salary_month, 0, 7);
        $this->amount = $payment->amount;
        $this->deduction = $payment->deduction;
        $this->paid_amount = $payment->paid_amount;
        $this->payment_date = $payment->payment_date;
        $this->payment_mode = $payment->payment_mode;
        $this->remarks = $payment->remarks;
    }

    public function delete($id)
    {
        TeacherSalaryPayment::findOrFail($id)->delete();

        session()->flash('success', 'Salary Payment Deleted Successfully.');
    }

    public function resetForm()
    {
        $this->reset([
            'salary_payment_id', 'teacher_id', 'amount', 'deduction',
            'paid_amount', 'remarks',
        ]);

        $this->salary_month = now()->format('Y-m');
        $this->payment_date = now()->format('Y-m-d');
        $this->payment_mode = 'Cash';
    }

    public function render()
    {
        return view(
            'livewire.admin.expense-management.salary-payment.index',
            [
                'payments' => TeacherSalaryPayment::with('teacher')
                    ->when($this->search, fn ($q) => $q->whereHas('teacher', fn ($t) => $t->where('name', 'like', '%' . $this->search . '%')))
                    ->latest('salary_month')
                    ->paginate(10),

                'teachers' => Teacher::where('status', 1)->get(),
            ]
        )->layout('layouts.admin');
    }
}
