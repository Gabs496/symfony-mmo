<?php

namespace App\GameObject\Mastery\Combat;

readonly class MaxHealth extends BaseCombatMastery
{

    public static function getName(): string
    {
        return 'Max Health';
    }

    public static function getId(): string
    {
        return 'MAX_HEALTH';
    }
}