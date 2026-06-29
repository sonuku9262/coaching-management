<?php

namespace App\Livewire\Admin\Permission;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Permission;

class Index extends Component
{
    use WithPagination;

    public $search = '';

    public $permission_id;
    public $name;
    public $slug;
    public $module;
    public $status = true;

    protected $rules = [
        'name'   => 'required|min:3',
        'slug'   => 'required',
        'module' => 'required',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function save()
    {
        $this->validate();

        Permission::updateOrCreate(

            ['id' => $this->permission_id],

            [

                'name'   => $this->name,
                'slug'   => $this->slug,
                'module' => $this->module,
                'status' => $this->status,

            ]

        );

        session()->flash(
            'success',
            $this->permission_id
                ? 'Permission Updated Successfully.'
                : 'Permission Added Successfully.'
        );

        $this->resetForm();
    }

    public function edit($id)
    {
        $permission = Permission::findOrFail($id);

        $this->permission_id = $permission->id;
        $this->name = $permission->name;
        $this->slug = $permission->slug;
        $this->module = $permission->module;
        $this->status = $permission->status;
    }

    public function delete($id)
    {
        Permission::findOrFail($id)->delete();

        session()->flash(
            'success',
            'Permission Deleted Successfully.'
        );
    }

    public function resetForm()
    {
        $this->reset([
            'permission_id',
            'name',
            'slug',
            'module',
        ]);

        $this->status = true;
    }

    public function render()
    {
        return view(
            'livewire.admin.permission.index',
            [
                'permissions' => Permission::where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('module', 'like', '%' . $this->search . '%')
                    ->latest()
                    ->paginate(10),
            ]
        )->layout('layouts.admin');
    }
}