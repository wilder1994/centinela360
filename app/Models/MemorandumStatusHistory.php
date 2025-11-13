<?php

namespace App\Models;

use App\Enums\MemorandumStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MemorandumStatusHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'memorandum_id',
        'from_status',
        'to_status',
        'changed_by',
        'notes',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'from_status' => MemorandumStatus::class,
        'to_status' => MemorandumStatus::class,
    ];

    public function memorandum(): BelongsTo
    {
        return $this->belongsTo(Memorandum::class);
    }

    public function changer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
