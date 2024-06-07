<?php

namespace App\Support\Enums;

enum TiposDecreto: string
{
    case DECRETO = 'D';
    case RESOLUCAO_MESA = 'M';

    public static function toArray(): array
    {
        return [
            self::DECRETO->value,
            self::RESOLUCAO_MESA->value,
        ];
    }

    public static function getLabel(string $value): string
    {
        switch ($value) {
            case self::DECRETO->value:
                return 'Decreto';
            case self::RESOLUCAO_MESA->value:
                return 'Resolução da Mesa';
        }
    }
}
