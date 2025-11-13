<?php

namespace App\Models;

use App\Enums\MemorandumStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Memorandum extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'user_id',
        'employee_id',
        'subject',
        'body',
        'status',
        'issued_at',
        'acknowledged_at',
    ];

    protected $casts = [
        'issued_at' => 'datetime',
        'acknowledged_at' => 'datetime',
        'status' => MemorandumStatus::class,
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function scopeStatus(Builder $query, MemorandumStatus $status): Builder
    {
        return $query->where('status', $status->value);
    }
}
