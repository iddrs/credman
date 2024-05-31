<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rubrica extends Model
{
    use HasFactory;

    protected $fillable = [
        'exercicio',
        'acesso',
        'uniorcam',
        'projativ',
        'despesa',
        'fonte',
        'complemento'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function creditos(): HasMany
    {
        return $this->hasMany(Credito::class);
    }

    public function reducoes(): HasMany
    {
        return $this->hasMany(Reducao::class);
    }
}
