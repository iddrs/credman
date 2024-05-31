<?php

namespace App\Support\Helpers;

class Fmt {
    public static function projativ($value): string
    {
        if(is_null($value)) {
            return '';
        }
        $projativ = str_pad($value, 4, '0', STR_PAD_LEFT);
        return substr($projativ, 0, 1) . '.' . substr($projativ, 1);
    }

    public static function receita($value): string
    {
        if(is_null($value)) {
            return '';
        }
        $n1 = substr($value, 0, 1);
        $n2 = substr($value, 1, 1);
        $n3 = substr($value, 2, 1);
        $n4 = substr($value, 3, 1);
        $n5 = substr($value, 4, 2);
        $n6 = substr($value, 6, 1);
        $n7 = substr($value, 7, 1);
        $n8 = substr($value, 8, 2);
        $n9 = substr($value, 10, 2);
        $n10 = substr($value, 12, 2);
        return $n1 . '.' . $n2 . '.' . $n3 . '.' . $n4 . '.' . $n5 . '.' . $n6 . '.' . $n7 . '.' . $n8 . '.' . $n9 . '.' . $n10;
    }

    public static function despesa($value): string
    {
        if(is_null($value)) {
            return '';
        }
        $n1 = substr($value, 0, 1);
        $n2 = substr($value, 1, 1);
        $n3 = substr($value, 2, 2);
        $n4 = substr($value, 4, 2);
        return $n1 . '.' . $n2 . '.' . $n3 . '.' . $n4;
    }

    public static function uniorcam($value): string
    {
        if(is_null($value)) {
            return '';
        }
        $uniorcam = str_pad($value, 4, '0', STR_PAD_LEFT);
        return substr($uniorcam, 0, 2) . '.' . substr($uniorcam, 2);
    }

    public static function fonte($value): string
    {
        if(is_null($value)) {
            return '';
        }
        return substr($value, 0, 3) . '.' . substr($value, 3);
    }

    public static function money($value): string
    {
        if(is_null($value)) {
            return '';
        }
        return number_format($value, 2, ',', '.');
    }

    public static function docnumber($value): string
    {
        if(is_null($value)) {
            return '';
        }
        return number_format($value, 0, null, '.');
    }


        public static function date($value): string
        {
            if(is_null($value)) {
                return '';
            }
            return date_create_from_format('Y-m-d', $value)->format('d/m/Y');
        }

        public static function dataPorExtenso($data)
        {
            $splitted = explode('-', $data);
            $dia = (int) $splitted[2];
            $mes = (int) $splitted[1];
            $ano = (int) $splitted[0];
            $meses = [
                1 => 'janeiro',
                2 => 'fevereiro',
                3 => 'março',
                4 => 'abril',
                5 => 'maio',
                6 => 'junho',
                7 => 'julho',
                8 => 'agosto',
                9 => 'setembro',
                10 => 'outubro',
                11 => 'novembro',
                12 => 'dezembro'
            ];
            return $dia . ' de ' . $meses[$mes] . ' de ' . $ano;
        }
}
