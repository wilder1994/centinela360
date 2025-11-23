<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'position',
        'document_type',
        'document_number',
        'rh',
        'address',
        'birth_date',
        'start_date',
        'badge_expires_at',
        'client_id',
        'service_type',
        'status',
        'emergency_contact_name',
        'emergency_contact_phone',
        'notes',
        'photo_path',          // 👈 AGREGA ESTA LÍNEA
    ];


    protected $casts = [
        'birth_date' => 'date',
        'start_date' => 'date',
        'badge_expires_at' => 'date',
    ];

    protected $appends = ['photo_url'];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function memorandums(): HasMany
    {
        return $this->hasMany(Memorandum::class);
    }

    public function fullName(): Attribute
    {
        return Attribute::get(fn () => trim($this->first_name . ' ' . $this->last_name));
    }

    public function scopeForCompany($query, int $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    public function scopeSearch($query, ?string $term)
    {
        if (!$term) {
            return $query;
        }

        return $query->where(function ($builder) use ($term) {
            $builder
                ->where('first_name', 'like', "%{$term}%")
                ->orWhere('last_name', 'like', "%{$term}%")
                ->orWhere('document_number', 'like', "%{$term}%")
                ->orWhere('phone', 'like', "%{$term}%");
        });
    }

    public function getPhotoUrlAttribute(): string
    {
        if ($this->photo_path && Storage::disk('public')->exists($this->photo_path)) {
            return asset('storage/' . $this->photo_path);
        }

        return asset('images/default-avatar.png');
    }
}
