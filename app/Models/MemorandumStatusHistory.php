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
        'user_id',
        'previous_status',
        'new_status',
        'comment',
    ];

    protected $casts = [
        'previous_status' => MemorandumStatus::class,
        'new_status'      => MemorandumStatus::class,
    ];

    public function memorandum(): BelongsTo
    {
        return $this->belongsTo(Memorandum::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
