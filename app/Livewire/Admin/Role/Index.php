<?php

namespace App\Livewire\Admin\Role;

use App\Models\Role;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';

    public $name;
    public $slug;
    public $description;
    public $status = true;

    protected $rules = [
        'name' => 'required|min:3|max:100',
        'slug' => 'required|unique:roles,slug',
        'description' => 'nullable',
    ];

    public function save()
    {
        $this->validate();

        Role::create([
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'status' => $this->status,
        ]);

        session()->flash('success', 'Role Created Successfully.');

        $this->reset(['name', 'slug', 'description']);

        $this->status = true;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $roles = Role::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('slug', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate(10);

        return view('livewire.admin.role.index', compact('roles'))
            ->layout('layouts.admin');
    }

    public $role_id;
    public $isEdit = false;

    public function edit($id)
    {
        $role = Role::findOrFail($id);

        $this->role_id = $role->id;
        $this->name = $role->name;
        $this->slug = $role->slug;
        $this->description = $role->description;
        $this->status = $role->status;

        $this->isEdit = true;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required',
            'slug' => 'required|unique:roles,slug,' . $this->role_id,
        ]);

        $role = Role::findOrFail($this->role_id);

        $role->update([
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'status' => $this->status,
        ]);

        session()->flash('success', 'Role Updated Successfully.');

        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset([
            'role_id',
            'name',
            'slug',
            'description',
        ]);

        $this->status = true;
        $this->isEdit = false;
    }

    public function delete($id)
{
    Role::findOrFail($id)->delete();

    session()->flash('success','Role Deleted Successfully');
}
}
