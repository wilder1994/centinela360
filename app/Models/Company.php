<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'nit',
        'address',
        'phone',
        'email',
        'representative',
        'logo',
        'color_primary',
        'color_secondary',
        'color_text',
        'active',
        'status',
        'subscription_expires_at',
        'notes',
    ];

    protected $casts = [
        'active' => 'boolean',
        'subscription_expires_at' => 'date',
    ];

    // 🧭 Relación: una empresa tiene muchos usuarios
    public function users()
    {
        return $this->hasMany(User::class);
    }

    // ✅ Helper para obtener el logo con ruta pública
    public function getLogoUrlAttribute(): string
    {
        if ($this->logo && Storage::disk('public')->exists($this->logo)) {
            return asset('storage/' . $this->logo);
        }
        return asset('images/default-company.png'); // imagen por defecto
    }

    // ✅ Helper: estado legible (para vistas o dashboard)
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'active' => 'Activa',
            'suspended' => 'Suspendida',
            'inactive' => 'Inactiva',
            default => 'Desconocido',
        };
    }

    // ✅ Helper: empresa activa o con suscripción vigente
    public function isActive(): bool
    {
        if (!$this->active || $this->status !== 'active') {
            return false;
        }

        return !$this->subscription_expires_at || $this->subscription_expires_at->isFuture();
    }
}
