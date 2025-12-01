<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'business_name',
        'nit',
        'address',
        'neighborhood',
        'city',
        'service_count',
        'email',
        'representative_name',
        'quadrant',
    ];

    protected $casts = [
        'service_count' => 'integer',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function services(): HasMany
    {
        return $this->hasMany(ClientService::class);
    }

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class, 'client_id');
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
            $builder->where('business_name', 'like', "%{$term}%")
                ->orWhere('nit', 'like', "%{$term}%")
                ->orWhere('city', 'like', "%{$term}%")
                ->orWhere('neighborhood', 'like', "%{$term}%");
        });
    }

    public function getServiceSummaryAttribute(): string
    {
        if ($this->relationLoaded('services')) {
            return $this->services
                ->map(fn (ClientService $service) => $service->service_type . ' • ' . $service->service_schedule)
                ->implode(', ');
        }

        return '';
    }
}
