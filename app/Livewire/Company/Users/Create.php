<?php

namespace App\Livewire\Company\Users;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Create extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public $role = '';
    public $roles = [];

    public function mount(): void
    {
        $this->roles = Role::orderBy('name')->pluck('name', 'id')->toArray();
    }

    public function save()
    {
        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'exists:roles,id'],
        ]);

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'company_id' => Auth::user()?->company_id,
            'is_active' => true,
            'active' => true,
        ]);

        $user->roles()->sync([$this->role]);

        session()->flash('status', 'Usuario creado correctamente.');

        return redirect()->route('company.users.index');
    }

    public function render()
    {
        return view('livewire.company.users.create', [
            'roles' => $this->roles,
        ])->layout('layouts.company');
    }
}
