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
    public $module;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function save()
    {
        abort_unless(auth()->user()->can($this->permission_id ? 'permissions.edit' : 'permissions.create'), 403);

        $this->validate([
            'name'   => 'required|min:3|unique:permissions,name,' . ($this->permission_id ?? 'NULL'),
            'module' => 'required',
        ]);

        Permission::updateOrCreate(
            ['id' => $this->permission_id],
            [
                'name'       => $this->name,
                'guard_name' => 'web',
                'module'     => $this->module,
            ],
        );

        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

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
        abort_unless(auth()->user()->can('permissions.edit'), 403);

        $permission = Permission::findOrFail($id);

        $this->permission_id = $permission->id;
        $this->name = $permission->name;
        $this->module = $permission->module;
    }

    public function delete($id)
    {
        abort_unless(auth()->user()->can('permissions.delete'), 403);

        Permission::findOrFail($id)->delete();

        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

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
            'module',
        ]);
    }

    public function render()
    {
        return view(
            'livewire.admin.permission.index',
            [
                'permissions' => Permission::where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('module', 'like', '%' . $this->search . '%')
                    ->orderBy('module')
                    ->orderBy('name')
                    ->paginate(15),
                'modules' => Permission::whereNotNull('module')
                    ->distinct()
                    ->orderBy('module')
                    ->pluck('module'),
            ]
        )->layout('layouts.admin');
    }
}
