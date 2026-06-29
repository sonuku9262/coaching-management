<?php

namespace App\Livewire\Admin\FeeManagement\FeeStructure;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\FeeStructure;
use App\Models\Course;
use App\Models\FeeType;

class Index extends Component
{
    use WithPagination;

    public $search='';

    public $fee_structure_id;
    public $course_id;
    public $fee_type_id;
    public $amount;
    public $installments=1;
    public $status=true;

    protected $rules=[
        'course_id'=>'required',
        'fee_type_id'=>'required',
        'amount'=>'required|numeric|min:0',
        'installments'=>'required|integer|min:1',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function save()
    {
        $this->validate();

        FeeStructure::updateOrCreate(
            ['id'=>$this->fee_structure_id],
            [
                'course_id'=>$this->course_id,
                'fee_type_id'=>$this->fee_type_id,
                'amount'=>$this->amount,
                'installments'=>$this->installments,
                'status'=>$this->status,
            ]
        );

        session()->flash(
            'success',
            $this->fee_structure_id
                ? 'Fee Structure Updated Successfully.'
                : 'Fee Structure Added Successfully.'
        );

        $this->resetForm();
    }

    public function edit($id)
    {
        $fee = FeeStructure::findOrFail($id);

        $this->fee_structure_id = $fee->id;
        $this->course_id = $fee->course_id;
        $this->fee_type_id = $fee->fee_type_id;
        $this->amount = $fee->amount;
        $this->installments = $fee->installments;
        $this->status = $fee->status;
    }

    public function delete($id)
    {
        FeeStructure::findOrFail($id)->delete();

        session()->flash('success','Fee Structure Deleted Successfully.');
    }

    public function resetForm()
    {
        $this->reset([
            'fee_structure_id',
            'course_id',
            'fee_type_id',
            'amount',
            'installments',
        ]);

        $this->status=true;
        $this->installments=1;
    }

    public function render()
    {
        return view('livewire.admin.fee-management.fee-structure.index',[
            'feeStructures'=>FeeStructure::with(['course','feeType'])
                ->latest()
                ->paginate(10),

            'courses'=>Course::where('status',1)->get(),

            'feeTypes'=>FeeType::where('status',1)->get(),
        ])->layout('layouts.admin');
    }
}