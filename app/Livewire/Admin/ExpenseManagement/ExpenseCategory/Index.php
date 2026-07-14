<?php

namespace App\Livewire\Admin\ExpenseManagement\ExpenseCategory;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ExpenseCategory;

class Index extends Component
{
    use WithPagination;

    public $search = '';

    public $expense_category_id;
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

        ExpenseCategory::updateOrCreate(
            ['id' => $this->expense_category_id],
            [
                'name' => $this->name,
                'code' => $this->code,
                'description' => $this->description,
                'status' => $this->status,
            ]
        );

        session()->flash(
            'success',
            $this->expense_category_id
                ? 'Expense Category Updated Successfully.'
                : 'Expense Category Added Successfully.'
        );

        $this->resetForm();
    }

    public function edit($id)
    {
        $category = ExpenseCategory::findOrFail($id);

        $this->expense_category_id = $category->id;
        $this->name = $category->name;
        $this->code = $category->code;
        $this->description = $category->description;
        $this->status = $category->status;
    }

    public function delete($id)
    {
        ExpenseCategory::findOrFail($id)->delete();

        session()->flash('success', 'Expense Category Deleted Successfully.');
    }

    public function resetForm()
    {
        $this->reset(['expense_category_id', 'name', 'code', 'description']);

        $this->status = true;
    }

    public function render()
    {
        return view(
            'livewire.admin.expense-management.expense-category.index',
            [
                'categories' => ExpenseCategory::where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('code', 'like', '%' . $this->search . '%')
                    ->latest()
                    ->paginate(10),
            ]
        )->layout('layouts.admin');
    }
}
