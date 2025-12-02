<?php

namespace App\Livewire\Company\Users;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Edit extends Component
{
    public User $user;

    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public array $rolesSelected = [];
    public $roles = [];

    public function mount(User $user): void
    {
        $auth = Auth::user();
        abort_if(! $auth?->isCompanyAdmin(), 403);
        abort_if($user->isSuperAdmin(), 403);
        abort_if($user->company_id !== $auth->company_id, 403);

        $this->user = $user;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->roles = Role::where('id', '!=', 1)
            ->orderBy('name')
            ->pluck('name', 'id')
            ->toArray();
        $this->rolesSelected = $user->roles()
            ->where('roles.id', '!=', 1)
            ->pluck('roles.id')
            ->toArray();
    }

    public function update(): void
    {
        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($this->user->id),
            ],
            'rolesSelected' => ['required', 'array'],
            'rolesSelected.*' => ['exists:roles,id', 'not_in:1'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        if (in_array(1, $this->rolesSelected, true)) {
            abort(403);
        }

        $this->user->name = $this->name;
        $this->user->email = $this->email;

        if ($this->password) {
            $this->user->password = Hash::make($this->password);
        }

        $this->user->save();
        $this->user->roles()->sync($this->rolesSelected);

        session()->flash('status', 'Usuario actualizado correctamente.');

        redirect()->route('company.users.index');
    }

    public function render()
    {
        return view('livewire.company.users.edit', [
            'roles' => $this->roles,
        ])->layout('layouts.company');
    }
}
