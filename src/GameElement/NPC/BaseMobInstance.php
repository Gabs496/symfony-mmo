<?php

namespace App\GameElement\NPC;

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

    public function setCurrentHealth(float $currentHealth): void
    {
        $this->currentHealth = $currentHealth;
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

    public function getMaxHealth(): float
    {
        return $this->getMob()->getMaxHealth();
    }
}