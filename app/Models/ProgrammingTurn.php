<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgrammingTurn extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'name',
        'description',
        'color',
        'position',
    ];

    protected $casts = [
        'position' => 'integer',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
