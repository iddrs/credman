<?php

namespace App\Support\Enums;

enum TiposOrigem: string
{
    case REDUCAO = 'Redução';
    case SUPERAVIT = 'Superávit';
    case EXCESSO = 'Excesso de arrecadação';
    case REABERTURA = 'Reabertura';
    case NENHUMA = 'Nenhuma';

    public static function toArray(): array
    {
        return [
            1 => self::REDUCAO->value,
            2 => self::SUPERAVIT->value,
            3 => self::EXCESSO->value,
            4 => self::REABERTURA->value,
            5 => self::NENHUMA->value,
        ];
    }
}
