<?php

namespace App\GameObject\Mastery\Combat;

readonly class PhysicalAttack extends BaseCombatMastery
{
    public const string ID = 'PHYSICAL_ATTACK';

    public static function getId(): string
    {
        return self::ID;
    }

    public static function getName(): string
    {
        return "Physical Attack";
    }
}