<?php

namespace App\Support;

/**
 * Amounts are stored in kobo (1 naira = 100 kobo) so there are no rounding errors.
 */
class Money
{
    public static function naira(int $kobo): string
    {
        return '₦'.number_format($kobo / 100, 2);
    }

    public static function toKobo(string|int|float $naira): int
    {
        return (int) round(((float) $naira) * 100);
    }

    public static function toNaira(int $kobo): string
    {
        return number_format($kobo / 100, 2, '.', '');
    }
}
