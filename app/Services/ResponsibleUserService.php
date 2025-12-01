<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class ResponsibleUserService
{
    public function getResponsables(): Collection
    {
        $roles = config('memorandums.responsable_roles', []);
        $ttl = config('memorandums.responsables_cache_ttl', 600);
        $companyId = Auth::user()?->company_id;

        $cacheKey = 'memorandum-responsables:' . ($companyId ?: 'all');

        return Cache::remember($cacheKey, $ttl, function () use ($roles, $companyId) {
            return User::query()
                ->where('active', true)
                ->when($companyId, fn ($query) => $query->where('company_id', $companyId))
                ->whereHas('roles', fn ($query) => $query->whereIn('name', $roles))
                ->orderBy('name')
                ->get();
        });
    }
}
