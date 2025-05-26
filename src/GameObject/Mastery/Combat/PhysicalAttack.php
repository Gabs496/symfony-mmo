<?php

namespace App\GameObject\Mastery\Combat;

readonly class PhysicalAttack extends BaseCombatMastery
{

    public static function getId(): string
    {
        return 'PHYSICAL_ATTACK';
    }

    public static function getName(): string
    {
        return "Physical Attack";
    }
}