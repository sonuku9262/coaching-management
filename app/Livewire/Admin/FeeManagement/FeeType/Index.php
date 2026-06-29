<?php

namespace App\Livewire\Admin\FeeManagement\FeeType;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\FeeType;

class Index extends Component
{
    use WithPagination;

    public $search = '';

    public $fee_type_id;
    public $name;
    public $code;
    public $description;
    public $status = true;

    protected $rules = [
        'name' => 'required|min:3|max:100',
        'code' => 'required|min:2|max:50',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function save()
    {
        $this->validate();

        FeeType::updateOrCreate(

            ['id' => $this->fee_type_id],

            [

                'name' => $this->name,

                'code' => $this->code,

                'description' => $this->description,

                'status' => $this->status,

            ]

        );

        session()->flash(
            'success',
            $this->fee_type_id
                ? 'Fee Type Updated Successfully.'
                : 'Fee Type Added Successfully.'
        );

        $this->resetForm();
    }

    public function edit($id)
    {
        $feeType = FeeType::findOrFail($id);

        $this->fee_type_id = $feeType->id;
        $this->name = $feeType->name;
        $this->code = $feeType->code;
        $this->description = $feeType->description;
        $this->status = $feeType->status;
    }

    public function delete($id)
    {
        FeeType::findOrFail($id)->delete();

        session()->flash(
            'success',
            'Fee Type Deleted Successfully.'
        );
    }

    public function resetForm()
    {
        $this->reset([
            'fee_type_id',
            'name',
            'code',
            'description',
        ]);

        $this->status = true;
    }

    public function render()
    {
        return view(
            'livewire.admin.fee-management.fee-type.index',
            [
                'feeTypes' => FeeType::where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('code', 'like', '%' . $this->search . '%')
                    ->latest()
                    ->paginate(10),
            ]
        )->layout('layouts.admin');
    }
}