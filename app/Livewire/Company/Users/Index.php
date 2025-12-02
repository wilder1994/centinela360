<?php

namespace App\Livewire\Company\Users;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public int $perPage = 10;

    protected $paginationTheme = 'tailwind';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function toggleActive(int $id): void
    {
        $companyId = Auth::user()?->company_id;
        $user = User::where('company_id', $companyId)
            ->whereDoesntHave('roles', fn ($q) => $q->where('roles.id', 1))
            ->findOrFail($id);

        $user->is_active = ! $user->is_active;
        $user->save();

        session()->flash('status', 'Estado actualizado correctamente.');
    }

    public function render()
    {
        $companyId = Auth::user()?->company_id;

        $users = User::with('roles')
            ->where('company_id', $companyId)
            ->whereDoesntHave('roles', fn ($q) => $q->where('roles.id', 1))
            ->when($this->search, function ($query) {
                $term = '%' . $this->search . '%';
                $query->where(function ($q) use ($term) {
                    $q->where('name', 'like', $term)
                        ->orWhere('email', 'like', $term);
                });
            })
            ->orderBy('name')
            ->paginate($this->perPage);

        return view('livewire.company.users.index', [
            'users' => $users,
        ])->layout('layouts.company');
    }
}
