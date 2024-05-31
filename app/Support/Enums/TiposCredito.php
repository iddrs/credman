<?php

namespace App\Support\Enums;

enum TiposCredito: string
{
    case SUPLEMENTAR = 'Suplementar';
    case ESPECIAL = 'Especial';
    case EXTRAORDINARIO = 'Extraordinário';

    public static function toArray(): array
    {
        return [
            1 => self::SUPLEMENTAR->value,
            2 => self::ESPECIAL->value,
            3 => self::EXTRAORDINARIO->value,
        ];
    }
}
