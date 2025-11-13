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
     * Laravel pluralizes "Memorandum" as "memorandums" by default, but our
     * migrations create the table using the latin plural form "memoranda".
     * Explicitly set the table name so queries hit the correct relation and
     * the listing views receive data instead of failing silently.
     */
    protected $table = 'memoranda';

    public const STATUS_DRAFT = 'draft';
    public const STATUS_IN_REVIEW = 'in_review';
    public const STATUS_ACKNOWLEDGED = 'acknowledged';
    public const STATUS_ARCHIVED = 'archived';

    /**
     * Return the available statuses as plain strings.
     *
     * Using a method keeps evaluation at runtime so PHP does not attempt to
     * resolve enum values while compiling constants—preventing fatal errors
     * during commands like `artisan migrate:fresh --seed`.
     */
    public static function statuses(): array
    {
        return [
            self::STATUS_DRAFT,
            self::STATUS_IN_REVIEW,
            self::STATUS_ACKNOWLEDGED,
            self::STATUS_ARCHIVED,
        ];
    }

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

    protected $appends = ['code'];

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

    public function statusHistories(): HasMany
    {
        return $this->hasMany(MemorandumStatusHistory::class)->orderByDesc('created_at');
    }

    public function latestStatusHistory(): HasMany
    {
        return $this->statusHistories()->limit(1);
    }

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

    public function scopeStatus(Builder $query, MemorandumStatus $status): Builder
    {
        return $query->where('status', $status->value);
    }

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

    public function statusLabel(): Attribute
    {
        return Attribute::get(fn () => $this->status?->label());
    }
}
