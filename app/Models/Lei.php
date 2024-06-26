<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Lei extends Model
{
    use HasFactory;

    protected $fillable = [
        'nr',
        'data',
        'exercicio',
        'tipo',
        'bc_limite_exec',
        'bc_limite_leg',
        'user_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function decretos(): HasMany
    {
        return $this->hasMany(Decreto::class);
    }

    public function creditos(): HasManyThrough
    {
        return $this->hasManyThrough(Credito::class, Decreto::class);
    }

    public function reducoes(): HasManyThrough
    {
        return $this->hasManyThrough(Reducao::class, Decreto::class);
    }
}
