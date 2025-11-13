<?php

namespace App\Models;

use App\Enums\MemorandumStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

class Memorandum extends Model
{
    use HasFactory;

    /**
     * Catálogo de estados permitidos para los memorandos.
     * Estos deben corresponder con los valores del enum MemorandumStatus.
     */
    public const STATUSES = [
        'draft',
        'in_review',
        'acknowledged',
        'archived',
    ];

    /**
     * Estados considerados "activos" para tableros y vistas (similar a ESTADOS_ACTIVOS de Ticket).
     */
    public const ACTIVE_STATUSES = [
        'draft',
        'in_review',
    ];

    /**
     * Estados finales usados para métricas, filtros y vistas de "finalizados".
     */
    public const FINAL_STATUSES = [
        'acknowledged',
        'archived',
    ];

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

    protected $appends = [
        'code',
        'status_label',
    ];

    protected $casts = [
        'issued_at'       => 'datetime',
        'acknowledged_at' => 'datetime',
        'status'          => MemorandumStatus::class,
    ];

    /*
    |--------------------------------------------------------------------------
    | Relaciones
    |--------------------------------------------------------------------------
    */

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

    public function statusHistories(): HasMany
    {
        return $this->hasMany(MemorandumStatusHistory::class)->orderByDesc('created_at');
    }

    public function latestStatusHistory(): HasMany
    {
        return $this->statusHistories()->limit(1);
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeForCompany(Builder $query, int $companyId): Builder
    {
        return $query->where('company_id', $companyId);
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (blank($term)) {
            return $query;
        }

        $term = trim($term);

        return $query->where(function (Builder $builder) use ($term) {
            $builder
                ->where('subject', 'like', "%{$term}%")
                ->orWhere('body', 'like', "%{$term}%")
                ->orWhereHas('employee', fn (Builder $employeeQuery) => $employeeQuery
                    ->where('first_name', 'like', "%{$term}%")
                    ->orWhere('last_name', 'like', "%{$term}%"));
        });
    }

    /**
     * Filtrar por un estado concreto usando el enum (similar a scopeEstado de Ticket).
     */
    public function scopeStatus(Builder $query, MemorandumStatus $status): Builder
    {
        return $query->where('status', $status->value);
    }

    /**
     * Filtrar memorandos "activos" (no finalizados), similar a scopeActivos de Ticket.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->whereIn('status', self::ACTIVE_STATUSES);
    }

    /**
     * Filtrar memorandos en estados finales (acknowledged / archived).
     */
    public function scopeFinal(Builder $query): Builder
    {
        return $query->whereIn('status', self::FINAL_STATUSES);
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers de estado (para usar en Livewire / vistas)
    |--------------------------------------------------------------------------
    */

    /**
     * Indica si el memorando está en uno de los estados "activos".
     */
    public function isActive(): bool
    {
        $status = $this->status instanceof MemorandumStatus
            ? $this->status->value
            : $this->status;

        return in_array($status, self::ACTIVE_STATUSES, true);
    }

    /**
     * Indica si el memorando está en un estado final.
     */
    public function isFinal(): bool
    {
        $status = $this->status instanceof MemorandumStatus
            ? $this->status->value
            : $this->status;

        return in_array($status, self::FINAL_STATUSES, true);
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors / Attributes
    |--------------------------------------------------------------------------
    */

    public function code(): Attribute
    {
        return Attribute::get(function () {
            if (!$this->exists) {
                return 'MEM-0000';
            }

            $year = $this->issued_at instanceof Carbon
                ? $this->issued_at->format('Y')
                : ($this->created_at?->format('Y') ?? now()->format('Y'));

            return sprintf('MEM-%s-%04d', $year, $this->id);
        });
    }

    /**
     * Devuelve la etiqueta legible del estado actual (usando el enum).
     */
    public function statusLabel(): Attribute
    {
        return Attribute::get(fn () => $this->status?->label());
    }
}
