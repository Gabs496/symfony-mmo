<?php

namespace App\GameElement\Mastery;

abstract readonly class MasteryType
{
    public static function getId(): string
    {
        return self::class;
    }

    public static abstract function getName(): string;
    public function __toString(): string
    {
        return static::getId();
    }
}
