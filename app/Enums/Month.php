<?php

declare(strict_types=1);

namespace App\Enums;

enum Month: int
{
    case Sausis = 1;
    case Vasaris = 2;
    case Kovas = 3;
    case Balandis = 4;
    case Gegužė = 5;
    case Birželis = 6;
    case Liepa = 7;
    case Rugpjūtis = 8;
    case Rugsėjis = 9;
    case Spalis = 10;
    case Lapkritis = 11;
    case Gruodis = 12;

    public static function getMonthName(int $month): string
    {
        return self::from($month)->name;
    }
}
