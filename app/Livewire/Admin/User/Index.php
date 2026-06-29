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
    public $role_id;
    public $status = true;

    protected $rules = [
        'name' => 'required|min:3|max:100',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6|confirmed',
        'role_id' => 'required',
    ];


    

    public function save()
    {
        $this->validate();

        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role_id' => $this->role_id,
            'status' => $this->status,
        ]);

        session()->flash('success', 'User Created Successfully.');

        $this->reset([
            'name',
            'email',
            'password',
            'password_confirmation',
            'role_id'
        ]);

        $this->status = true;
    }

    public function render()
    {
        $users = User::with('role')
            ->where('name', 'like', '%' . $this->search . '%')
            ->orWhere('email', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate(10);

        $roles = Role::where('status', true)->get();

        return view('livewire.admin.user.index', [
            'users' => $users,
            'roles' => $roles,
        ])->layout('layouts.admin');
    }
}
