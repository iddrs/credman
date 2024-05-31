<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function rubricas(): HasMany
    {
        return $this->hasMany(Rubrica::class);
    }

    public function leis(): HasMany
    {
        return $this->hasMany(Lei::class);
    }

    public function decretos(): HasMany
    {
        return $this->hasMany(Decreto::class);
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
