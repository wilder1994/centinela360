<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MemorandumLog extends Model
{
    use HasFactory;

    protected $table = 'memorandum_logs';

    protected $fillable = [
        'memorandum_id',
        'user_id',
        'estado_anterior',
        'estado_nuevo',
        'comentario',
    ];

    public function memorandum(): BelongsTo
    {
        return $this->belongsTo(Memorandum::class, 'memorandum_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
