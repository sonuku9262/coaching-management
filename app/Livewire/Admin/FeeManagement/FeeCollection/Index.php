<?php

namespace App\Livewire\Admin\FeeManagement\FeeCollection;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\FeeCollection;
use App\Models\StudentRegistration;
use App\Models\FeeType;
use App\Models\FeeStructure;

class Index extends Component
{
    use WithPagination;

    public $search = '';

    public $fee_collection_id;

    public $student_registration_id;
    public $fee_type_id;

    public $amount = 0;
    public $discount = 0;
    public $fine = 0;
    public $paid_amount = 0;
    public $balance = 0;

    public $payment_mode = 'Cash';

    public $receipt_no;

    public $payment_date;

    public $remarks;

    public $status = true;

    protected $rules = [
        'student_registration_id' => 'required',
        'fee_type_id' => 'required',
        'amount' => 'required|numeric',
        'paid_amount' => 'required|numeric',
        'payment_date' => 'required',
    ];

    public function updated($propertyName)
    {
        $amount = (float) ($this->amount ?: 0);
        $fine = (float) ($this->fine ?: 0);
        $discount = (float) ($this->discount ?: 0);
        $paid = (float) ($this->paid_amount ?: 0);

        $this->balance = ($amount + $fine) - ($discount + $paid);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function save()
    {
        $this->validate();

        // Balance Calculate
        $amount = (float) ($this->amount ?: 0);
        $fine = (float) ($this->fine ?: 0);
        $discount = (float) ($this->discount ?: 0);
        $paid = (float) ($this->paid_amount ?: 0);

        $balance = ($amount + $fine) - ($discount + $paid);

        // Receipt No Generate (Only New Record)
        if (!$this->fee_collection_id) {

            $next = (FeeCollection::max('id') ?? 0) + 1;

            $this->receipt_no = 'RCPT-' . date('Y') . '-' . str_pad($next, 5, '0', STR_PAD_LEFT);
        }

        FeeCollection::updateOrCreate(

            ['id' => $this->fee_collection_id],

            [

                'student_registration_id' => $this->student_registration_id,

                'fee_type_id' => $this->fee_type_id,

                'amount' => $amount,

                'discount' => $discount,

                'fine' => $fine,

                'paid_amount' => $paid,

                'balance' => $balance,

                'payment_mode' => $this->payment_mode,

                'receipt_no' => $this->receipt_no,

                'payment_date' => $this->payment_date,

                'remarks' => $this->remarks,

                'status' => $this->status,

            ]

        );

        session()->flash(
            'success',
            $this->fee_collection_id
                ? 'Fee Collection Updated Successfully.'
                : 'Fee Collected Successfully.'
        );

        $this->resetForm();
    }

    public function edit($id)
    {
        $fee = FeeCollection::findOrFail($id);

        $this->fee_collection_id = $fee->id;

        $this->student_registration_id = $fee->student_registration_id;

        $this->fee_type_id = $fee->fee_type_id;

        $this->amount = $fee->amount;

        $this->discount = $fee->discount;

        $this->fine = $fee->fine;

        $this->paid_amount = $fee->paid_amount;

        $this->balance = $fee->balance;

        $this->payment_mode = $fee->payment_mode;

        $this->receipt_no = $fee->receipt_no;

        $this->payment_date = $fee->payment_date;

        $this->remarks = $fee->remarks;

        $this->status = $fee->status;
    }

    public function delete($id)
    {
        FeeCollection::findOrFail($id)->delete();

        session()->flash(
            'success',
            'Fee Collection Deleted Successfully.'
        );
    }

    public function resetForm()
    {
        $this->reset([

            'fee_collection_id',

            'student_registration_id',

            'fee_type_id',

            'amount',

            'discount',

            'fine',

            'paid_amount',

            'balance',

            'receipt_no',

            'payment_date',

            'remarks',

        ]);

        $this->payment_mode = 'Cash';

        $this->status = true;
    }

    public function updatedStudentRegistrationId()
    {
        $this->loadFeeAmount();
    }

    public function updatedFeeTypeId()
    {
        $this->loadFeeAmount();
    }

    public function loadFeeAmount()
    {
        if (!$this->student_registration_id || !$this->fee_type_id) {
            return;
        }

        $student = StudentRegistration::find($this->student_registration_id);

        if (!$student) {
            return;
        }

        $fee = FeeStructure::where('course_id', $student->course_id)
            ->where('fee_type_id', $this->fee_type_id)
            ->first();

        if ($fee) {

            $this->amount = $fee->amount;

            $this->discount = 0;

            $this->fine = 0;

            $this->paid_amount = $fee->amount;

            $this->balance = 0;
        }
    }

    public function render()
    {
        return view(
            'livewire.admin.fee-management.fee-collection.index',
            [

                'collections' => FeeCollection::with([
                    'student',
                    'feeType'
                ])
                    ->latest()
                    ->paginate(10),

                'students' => StudentRegistration::where('status', 1)->get(),

                'feeTypes' => FeeType::where('status', 1)->get(),

            ]
        )->layout('layouts.admin');
    }
}
