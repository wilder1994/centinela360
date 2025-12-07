<?php

namespace App\Models;

use Carbon\Carbon;
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
        'client_id',
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
        'service_type',
        'status',
        'emergency_contact_name',
        'emergency_contact_phone',
        'notes',
        'photo_path',
        'archived_at',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'start_date' => 'date',
        'badge_expires_at' => 'date',
        'archived_at' => 'datetime',
    ];

    protected $appends = ['photo_url'];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function activityNotes(): HasMany
    {
        return $this->hasMany(EmployeeNote::class);
    }

    public function fullName(): Attribute
    {
        return Attribute::get(fn () => trim($this->first_name . ' ' . $this->last_name));
    }

    public function scopeForCompany($query, int $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    public function scopeOnlyArchived($query)
    {
        return $query->whereNotNull('archived_at');
    }

    public function scopeWithoutArchived($query)
    {
        return $query->whereNull('archived_at');
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

    public function getTenureAttribute(): string
    {
        if (!$this->start_date) {
            return '';
        }

        $end = Carbon::now();
        $start = Carbon::parse($this->start_date);
        $diff = $start->diff($end);

        $parts = [];
        if ($diff->y) {
            $parts[] = $diff->y . ' ' . ($diff->y === 1 ? 'año' : 'años');
        }
        if ($diff->m) {
            $parts[] = $diff->m . ' ' . ($diff->m === 1 ? 'mes' : 'meses');
        }

        return $parts ? implode(' ', $parts) : 'Reciente';
    }
}
