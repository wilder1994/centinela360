<?php

namespace App\Models;

use Carbon\Carbon;
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
        'start_date',
        'end_date',
        'archived_at',
    ];

    protected $casts = [
        'service_count' => 'integer',
        'start_date' => 'date',
        'end_date' => 'date',
        'archived_at' => 'datetime',
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

    public function notes(): HasMany
    {
        return $this->hasMany(ClientNote::class);
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
            $builder->where('business_name', 'like', "%{$term}%")
                ->orWhere('nit', 'like', "%{$term}%")
                ->orWhere('city', 'like', "%{$term}%")
                ->orWhere('address', 'like', "%{$term}%")
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

    public function getTenureAttribute(): string
    {
        if (!$this->start_date) {
            return '';
        }

        $end = $this->end_date ? Carbon::parse($this->end_date) : Carbon::now();
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
