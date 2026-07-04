<?php

namespace App\Livewire\Admin\User;

use App\Models\Role;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;

class Index extends Component
{
    use WithPagination;

    public $search = '';

    public $user_id;
    public $name;
    public $email;
    public $password;
    public $password_confirmation;
    public $role;
    public $status = true;
    public $isEdit = false;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function save()
    {
        abort_unless(auth()->user()->can('users.create'), 403);

        $this->validate([
            'name' => 'required|min:3|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'role' => 'required|exists:roles,name',
        ]);

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'status' => $this->status,
        ]);

        $user->syncRoles([$this->role]);

        session()->flash('success', 'User Created Successfully.');

        $this->resetForm();
    }

    public function edit($id)
    {
        abort_unless(auth()->user()->can('users.edit'), 403);

        $user = User::findOrFail($id);

        $this->user_id = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->roles->first()?->name;
        $this->status = (bool) $user->status;
        $this->password = null;
        $this->password_confirmation = null;
        $this->isEdit = true;
    }

    public function update()
    {
        abort_unless(auth()->user()->can('users.edit'), 403);

        $this->validate([
            'name' => 'required|min:3|max:100',
            'email' => 'required|email|unique:users,email,' . $this->user_id,
            'password' => 'nullable|min:8|confirmed',
            'role' => 'required|exists:roles,name',
        ]);

        $user = User::findOrFail($this->user_id);

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'status' => $this->status,
        ];

        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        $user->update($data);

        // only a super-admin can grant or revoke the super-admin role
        $touchesSuperAdmin = $this->role === 'super-admin' || $user->hasRole('super-admin');
        if (! $touchesSuperAdmin || auth()->user()->hasRole('super-admin')) {
            $user->syncRoles([$this->role]);
        }

        session()->flash('success', 'User Updated Successfully.');

        $this->resetForm();
    }

    public function delete($id)
    {
        abort_unless(auth()->user()->can('users.delete'), 403);

        if ((int) $id === auth()->id()) {
            session()->flash('error', 'You cannot delete your own account.');
            return;
        }

        $user = User::findOrFail($id);

        if ($user->hasRole('super-admin') && ! auth()->user()->hasRole('super-admin')) {
            session()->flash('error', 'Only a super admin can delete a super admin.');
            return;
        }

        $user->delete();

        session()->flash('success', 'User Deleted Successfully.');
    }

    public function resetForm()
    {
        $this->reset([
            'user_id',
            'name',
            'email',
            'password',
            'password_confirmation',
            'role',
        ]);

        $this->status = true;
        $this->isEdit = false;
    }

    public function render()
    {
        $users = User::with('roles')
            ->where(function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(10);

        $roles = Role::orderBy('name')->get();

        return view('livewire.admin.user.index', [
            'users' => $users,
            'roles' => $roles,
        ])->layout('layouts.admin');
    }
}
