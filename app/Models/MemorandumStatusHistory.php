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
        'from_status',   // 👈 nombre real de la columna
        'to_status',     // 👈 nombre real de la columna
        'changed_by',    // 👈 IMPORTANTE: agregar este
        'notes',
    ];

    protected $casts = [
        'from_status' => MemorandumStatus::class, // 👈 antes tenías previous_status
        'to_status'   => MemorandumStatus::class, // 👈 antes tenías new_status
    ];

    public function memorandum(): BelongsTo
    {
        return $this->belongsTo(Memorandum::class);
    }

    // relación para "Cambio realizado por ..."
    public function changer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}

