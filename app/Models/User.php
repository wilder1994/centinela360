<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'company_id',
        'phone',
        'active',
        'photo', // 👈 ESTE FALTABA
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'active' => 'boolean',
    ];

    // 🔗 Un usuario pertenece a una empresa
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    // 🔗 Un usuario puede tener varios roles
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }

    // 🔗 Un usuario tiene permisos a través de sus roles
    public function permissions()
    {
        return $this->roles()->with('permissions')->get()->pluck('permissions')->flatten()->unique('id');
    }

    // 🔐 Verificar si tiene un rol
    public function hasRole($roleName)
    {
        return $this->roles()->where('name', $roleName)->exists();
    }

    // 🔐 Verificar si tiene un permiso
    public function hasPermission($permissionCode)
    {
        return $this->roles()->whereHas('permissions', function ($query) use ($permissionCode) {
            $query->where('code', $permissionCode);
        })->exists();
    }
}
