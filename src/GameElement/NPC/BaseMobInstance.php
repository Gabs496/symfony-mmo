<?php

namespace App\GameElement\NPC;

use App\Engine\Math;

abstract class BaseMobInstance
{
    protected float $currentHealth;

    public function __construct(
        protected BaseMob $mob,
    )
    {
        $this->currentHealth = $mob->getMaxHealth();
    }

    public function getMob(): BaseMob
    {
        return $this->mob;
    }

    public function receiveDamage(float $damage): void
    {
        $this->currentHealth = max(Math::sub($this->currentHealth, $damage));
    }

    public function getCurrentHealth(): float
    {
        return $this->currentHealth;
    }

    public function getOffensiveStats(): array
    {
        return $this->getMob()->getOffensiveStats();
    }

    public function getDefensiveStats(): array
    {
        return $this->getMob()->getDefensiveStats();
    }
}