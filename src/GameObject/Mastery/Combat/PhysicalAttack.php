<?php

namespace App\GameObject\Mastery\Combat;

readonly class PhysicalAttack extends BaseCombatMastery
{

    public function __construct()
    {
        parent::__construct('PHYSICAL_ATTACK');
    }

    public static function getName(): string
    {
        return "Physical Attack";
    }
}