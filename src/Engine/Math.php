<?php

namespace App\Engine;

class Math
{
    public const int SCALE = 4;
    public const int ROUND = 2;

    public static function getStatViewValue(string $value): string
    {
        return number_format(round(bcmul($value, 100, self::SCALE)));
    }

    public static function add(string $a, string $b, bool $round = true): string
    {
        $result = bcadd($a, $b, self::SCALE);
        return $round ? self::round($result) : $result;
    }

    public static function sub(string $a, string $b, bool $round = true): string
    {
        $result = bcsub($a, $b, self::SCALE);
        return $round ? self::round($result) : $result;
    }

    public static function mul(string $a, string $b, bool $round = true): string
    {
        $result = bcmul($a, $b, self::SCALE);
        return $round ? self::round($result) : $result;
    }

    public static function div(string $a, string $b, bool $round = true): string
    {
        $result = bcdiv($a, $b, self::SCALE);
        return $round ? self::round($result) : $result;
    }

    public static function round(string $a): string
    {
        return round($a, self::ROUND);
    }

    public static function compare(string $a, string $b): int
    {
        return bccomp($a, $b, self::SCALE);
    }
}