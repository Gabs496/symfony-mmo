<?php

namespace App\GameElement\Combat;

interface CombatOpponentTokenInterface
{
    /**
     * @return class-string<CombatOpponentInterface>
     */
    public function getCombatOpponentClass(): string;
}