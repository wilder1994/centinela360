<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $auth = auth()->user();

        if (! $auth->isCompanyAdmin()) {
            abort(403);
        }

        $users = User::where('company_id', $auth->company_id)
            ->whereDoesntHave('roles', fn ($q) => $q->where('roles.id', 1)) // nunca mostrar Super Admin
            ->orderBy('name')
            ->get();

        return view('company.users.index', compact('users'));
    }

    public function create()
    {
        $auth = auth()->user();
        if (! $auth->isCompanyAdmin()) {
            abort(403);
        }

        // Provide roles so the company admin can assign them
        $roles = Role::where('id', '!=', 1)->orderBy('name')->get();
        return view('company.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $auth = auth()->user();
        if (! $auth->isCompanyAdmin()) {
            abort(403);
        }

        $companyId = $auth->company_id;

        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'email' => 'required|email|max:150|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'active' => 'sometimes|boolean',
            'roles' => 'nullable|array',
            'roles.*' => 'integer|exists:roles,id|not_in:1',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'] ?? null,
            'active' => $validated['active'] ?? true,
            'company_id' => $companyId,
        ]);

        if (!empty($validated['roles'])) {
            $user->roles()->sync(array_diff($validated['roles'], [1]));
        }

        return redirect()->route('company.users.index')
                         ->with('success', 'Usuario creado correctamente.');
    }

    public function edit(User $user)
    {
        $auth = auth()->user();
        if ($user->isSuperAdmin()) abort(403);
        if ($user->company_id !== $auth->company_id) abort(403);
        if (! $auth->isCompanyAdmin()) abort(403);

        $roles = Role::where('id', '!=', 1)->orderBy('name')->get();
        $userRoles = $user->roles()->where('roles.id', '!=', 1)->pluck('id')->toArray();

        return view('company.users.edit', compact('user', 'roles', 'userRoles'));
    }

    public function update(Request $request, User $user)
    {
        $auth = auth()->user();
        if ($user->isSuperAdmin()) abort(403);
        if ($user->company_id !== $auth->company_id) abort(403);
        if (! $auth->isCompanyAdmin()) abort(403);

        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'email' => [
                'required','email','max:150',
                Rule::unique('users','email')->ignore($user->id),
            ],
            'password' => 'nullable|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'active' => 'sometimes|boolean',
            'roles' => 'nullable|array',
            'roles.*' => 'integer|exists:roles,id|not_in:1',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'] ?? $user->phone;
        $user->active = $validated['active'] ?? $user->active;

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        $roles = array_diff($validated['roles'] ?? [], [1]);
        $user->roles()->sync($roles);

        return redirect()->route('company.users.index')
                         ->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy(User $user)
    {
        $auth = auth()->user();
        if ($user->isSuperAdmin()) abort(403);
        if ($user->company_id !== $auth->company_id) abort(403);
        if (! $auth->isCompanyAdmin()) abort(403);

        $user->roles()->sync([]); // detach roles
        $user->delete();

        return redirect()->route('company.users.index')
                         ->with('success', 'Usuario eliminado correctamente.');
    }
}
