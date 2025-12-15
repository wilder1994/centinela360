<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Company;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with(['company', 'roles'])
            ->orderBy('name')
            ->get();

        return view('admin.users.index', compact('users'));
    }

    public function toggle(User $user)
    {
        $user->active = !$user->active;
        $user->save();
        return back()->with('success', 'Estado del usuario actualizado.');
    }

    public function create()
    {
        $roles = Role::orderBy('name')->get();
        $companies = Company::orderBy('name')->get();
        return view('admin.users.create', compact('roles', 'companies'));
    }

    public function store(Request $request)
    {
        $messages = [
            'photo.image' => 'Debes subir una imagen válida.',
            'photo.mimes' => 'La foto debe ser JPG o PNG.',
            'photo.max' => 'La foto no debe superar 2 MB.',
        ];

        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'email' => 'required|email|max:150|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'company_id' => 'nullable|exists:companies,id',
            'roles' => 'nullable|array',
            'roles.*' => 'integer|exists:roles,id',
            'active' => 'boolean',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ], $messages);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('users/photos', 'public');
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'] ?? null,
            'company_id' => $validated['company_id'] ?? null,
            'active' => $request->boolean('active'),
            'photo' => $photoPath,
        ]);

        if (!empty($validated['roles'])) {
            $user->roles()->sync($validated['roles']);
        }

        return redirect()->route('admin.users.index')->with('success', 'Usuario creado correctamente.');
    }

    public function edit(User $user)
    {
        $roles = Role::orderBy('name')->get();
        $companies = Company::orderBy('name')->get();
        $userRoles = $user->roles()->pluck('roles.id')->toArray();

        return view('admin.users.edit', compact('user', 'roles', 'userRoles', 'companies'));
    }

    public function update(Request $request, User $user)
    {
        $messages = [
            'photo.image' => 'Debes subir una imagen válida.',
            'photo.mimes' => 'La foto debe ser JPG o PNG.',
            'photo.max' => 'La foto no debe superar 2 MB.',
        ];

        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'email' => [
                'required', 'email', 'max:150',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'password' => 'nullable|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'company_id' => 'nullable|exists:companies,id',
            'roles' => 'nullable|array',
            'roles.*' => 'integer|exists:roles,id',
            'active' => 'nullable|boolean',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ], $messages);

        // 🔹 Guardar foto actual o reemplazar si hay nueva
        $photoPath = $user->photo;

        if ($request->hasFile('photo')) {
            // eliminar foto anterior
            if ($photoPath && Storage::disk('public')->exists($photoPath)) {
                Storage::disk('public')->delete($photoPath);
            }

            // guardar nueva
            $photoPath = $request->file('photo')->store('users/photos', 'public');
        }

        // 🔹 Actualizar datos generales
        $user->fill([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'company_id' => $validated['company_id'] ?? null,
            'active' => $request->boolean('active'),
            'photo' => $photoPath,
        ]);

        // 🔹 Actualizar contraseña si se envía una nueva
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        // 🔹 Actualizar roles
        $user->roles()->sync($validated['roles'] ?? []);

        return redirect()->route('admin.users.index')->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy(User $user)
    {
        // 🧹 Eliminar foto del almacenamiento
        if ($user->photo && Storage::disk('public')->exists($user->photo)) {
            Storage::disk('public')->delete($user->photo);
        }

        // Eliminar relaciones y usuario
        $user->roles()->sync([]);
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Usuario eliminado correctamente.');
    }
}
