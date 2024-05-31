<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vinculo extends Model
{
    use HasFactory;

    protected $fillable = [
        'credito_id',
        'reducao_id',
        'excesso_id',
        'valor',
        'limite',
        'aviso',
        'justificativa',
        'decreto_id',
        'user_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function credito(): BelongsTo
    {
        return $this->belongsTo(Credito::class);
    }

    public function decreto(): BelongsTo
    {
        return $this->belongsTo(Decreto::class);
    }

    public function reducao(): BelongsTo
    {
        return $this->belongsTo(Reducao::class);
    }

    public function excesso(): BelongsTo
    {
        return $this->belongsTo(Excesso::class);
    }

    public function superavit(): BelongsTo
    {
        return $this->belongsTo(Superavit::class);
    }
}
