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
        $companyId = auth()->user()->company_id;
        $users = User::where('company_id', $companyId)->orderBy('name')->get();

        return view('company.users.index', compact('users'));
    }

    public function create()
    {
        // Provide roles so the company admin can assign them
        $roles = Role::orderBy('name')->get();
        return view('company.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $companyId = auth()->user()->company_id;

        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'email' => 'required|email|max:150|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'active' => 'sometimes|boolean',
            'roles' => 'nullable|array',
            'roles.*' => 'integer|exists:roles,id',
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
            $user->roles()->sync($validated['roles']);
        }

        return redirect()->route('company.users.index')
                         ->with('success', 'Usuario creado correctamente.');
    }

    public function edit(User $user)
    {
        // Ensure the user belongs to the same company
        $companyId = auth()->user()->company_id;
        if ($user->company_id !== $companyId) {
            abort(403);
        }

        $roles = Role::orderBy('name')->get();
        $userRoles = $user->roles()->pluck('id')->toArray();

        return view('company.users.edit', compact('user', 'roles', 'userRoles'));
    }

    public function update(Request $request, User $user)
    {
        $companyId = auth()->user()->company_id;
        if ($user->company_id !== $companyId) {
            abort(403);
        }

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
            'roles.*' => 'integer|exists:roles,id',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'] ?? $user->phone;
        $user->active = $validated['active'] ?? $user->active;

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        $user->roles()->sync($validated['roles'] ?? []);

        return redirect()->route('company.users.index')
                         ->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy(User $user)
    {
        $companyId = auth()->user()->company_id;
        if ($user->company_id !== $companyId) {
            abort(403);
        }

        $user->roles()->sync([]); // detach roles
        $user->delete();

        return redirect()->route('company.users.index')
                         ->with('success', 'Usuario eliminado correctamente.');
    }
}
