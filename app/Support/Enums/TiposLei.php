<?php

namespace App\Support\Enums;

enum TiposLei: string
{
    case LOA = 'LOA';
    case LDO = 'LDO';
    case CREDITO_ADICIONAL = 'Crédito Adicional';
    case OUTRA = 'Outra';

    public static function toArray(): array
    {
        return [
            self::LOA->value,
            self::LDO->value,
            self::CREDITO_ADICIONAL->value,
            self::OUTRA->value,
        ];
    }
}
