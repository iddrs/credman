<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Decreto extends Model
{
    use HasFactory;

    protected $fillable = [
        'nr',
        'data',
        'vl_credito',
        'vl_reducao',
        'vl_superavit',
        'vl_excesso',
        'vl_reaberto',
        'fechado',
        'lei_id',
        'user_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function lei(): BelongsTo
    {
        return $this->belongsTo(Lei::class);
    }

    public function creditos(): HasMany
    {
        return $this->hasMany(Credito::class);
    }

    public function reducoes(): HasMany
    {
        return $this->hasMany(Reducao::class);
    }

    public function excessos(): HasMany
    {
        return $this->hasMany(Excesso::class);
    }

    public function superavits(): HasMany
    {
        return $this->hasMany(Superavit::class);
    }

    public function vinculos(): HasMany
    {
        return $this->hasMany(Vinculo::class);
    }
}
