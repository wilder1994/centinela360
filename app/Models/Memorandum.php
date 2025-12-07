<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Memorandum extends Model
{
    use HasFactory;

    protected $table = 'memorandums';

    public const ESTADOS = ['pending', 'en_proceso', 'finalizado'];
    public const ESTADOS_ACTIVOS = ['pending', 'en_proceso'];
    public const ESTADOS_FINALIZADOS = ['finalizado'];

    protected $fillable = [
        'company_id',
        'author_id',
        'assigned_to',
        'approved_by',
        'puesto',
        'employee_name',
        'employee_document',
        'employee_position',
        'title',
        'body',
        'estado',
        'final_status',
        'prioridad',
        'vence_en',
    ];

    protected $casts = [
        'vence_en' => 'datetime',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function creador(): BelongsTo
    {
        return $this->author();
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function logs(): HasMany
    {
        return $this->hasMany(MemorandumLog::class, 'memorandum_id');
    }

    public function scopeEstado(Builder $query, string $estado): Builder
    {
        return $query->where('estado', $estado);
    }

    public function scopeActivos(Builder $query): Builder
    {
        return $query->whereIn('estado', self::ESTADOS_ACTIVOS);
    }

    public function scopeSearch(Builder $query, ?string $texto): Builder
    {
        if (!$texto) {
            return $query;
        }

        return $query->where(function (Builder $builder) use ($texto) {
            $builder
                ->where('title', 'like', "%{$texto}%")
                ->orWhere('body', 'like', "%{$texto}%")
                ->orWhere('prioridad', 'like', "%{$texto}%");
        });
    }

    public function isFinalizado(): bool
    {
        return $this->estado === 'finalizado';
    }

    public function isActivo(): bool
    {
        return in_array($this->estado, self::ESTADOS_ACTIVOS, true);
    }
}
