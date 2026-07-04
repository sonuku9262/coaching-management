<?php

namespace App\Livewire\Admin\Role;

use App\Models\Permission;
use App\Models\Role;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';

    public $role_id;
    public $name;
    public $selectedPermissions = [];
    public $isEdit = false;

    /**
     * Roles that ship with the system and cannot be renamed or deleted.
     */
    protected array $protectedRoles = ['super-admin', 'admin', 'accountant', 'teacher', 'student', 'parent'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function save()
    {
        abort_unless(auth()->user()->can('roles.create'), 403);

        $this->validate([
            'name' => 'required|min:3|max:100|unique:roles,name',
        ]);

        $role = Role::create(['name' => $this->name, 'guard_name' => 'web']);
        $role->syncPermissions($this->selectedPermissions);

        session()->flash('success', 'Role Created Successfully.');

        $this->resetForm();
    }

    public function edit($id)
    {
        abort_unless(auth()->user()->can('roles.edit'), 403);

        $role = Role::findOrFail($id);

        $this->role_id = $role->id;
        $this->name = $role->name;
        $this->selectedPermissions = $role->permissions->pluck('name')->toArray();
        $this->isEdit = true;
    }

    public function update()
    {
        abort_unless(auth()->user()->can('roles.edit'), 403);

        $this->validate([
            'name' => 'required|min:3|max:100|unique:roles,name,' . $this->role_id,
        ]);

        $role = Role::findOrFail($this->role_id);

        if (! in_array($role->name, $this->protectedRoles)) {
            $role->update(['name' => $this->name]);
        }

        // super-admin bypasses checks via Gate::before, no explicit grants needed
        if ($role->name !== 'super-admin') {
            $role->syncPermissions($this->selectedPermissions);
        }

        session()->flash('success', 'Role Updated Successfully.');

        $this->resetForm();
    }

    public function delete($id)
    {
        abort_unless(auth()->user()->can('roles.delete'), 403);

        $role = Role::findOrFail($id);

        if (in_array($role->name, $this->protectedRoles)) {
            session()->flash('error', 'System roles cannot be deleted.');
            return;
        }

        if ($role->users()->exists()) {
            session()->flash('error', 'Role is assigned to users and cannot be deleted.');
            return;
        }

        $role->delete();

        session()->flash('success', 'Role Deleted Successfully');
    }

    public function resetForm()
    {
        $this->reset(['role_id', 'name', 'selectedPermissions']);
        $this->isEdit = false;
    }

    public function render()
    {
        $roles = Role::withCount(['users', 'permissions'])
            ->where('name', 'like', '%' . $this->search . '%')
            ->orderBy('name')
            ->paginate(10);

        $permissionGroups = Permission::orderBy('module')->orderBy('name')
            ->get()
            ->groupBy('module');

        return view('livewire.admin.role.index', [
            'roles' => $roles,
            'permissionGroups' => $permissionGroups,
        ])->layout('layouts.admin');
    }
}
