<?php

namespace App\Support\Enums;

enum TiposCredito: string
{
    case SUPLEMENTAR = '1 Suplementar';
    case ESPECIAL = '2 Especial';
    case EXTRAORDINARIO = '3 Extraordinário';

    public static function toArray(): array
    {
        return [
            1 => self::SUPLEMENTAR->value,
            2 => self::ESPECIAL->value,
            3 => self::EXTRAORDINARIO->value,
        ];
    }
}
