<?php

namespace App\GameObject\Mastery\Combat;

readonly class MaxHealth extends BaseCombatMastery
{

    public function __construct()
    {
        parent::__construct('MAX_HEALTH');
    }

    public static function getName(): string
    {
        return 'Max Health';
    }
}