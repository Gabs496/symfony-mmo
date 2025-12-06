<?php

namespace App\GameElement\Health\Component;

use App\GameElement\Core\GameComponent\GameComponentInterface;

class HealthComponent implements GameComponentInterface
{
    public function __construct(
        protected float     $maxHealth,
        protected float     $currentHealth,

    ) {
    }

    public function getMaxHealth(): float
    {
        return $this->maxHealth;
    }

    public function setMaxHealth(float $maxHealth): void
    {
        $this->maxHealth = $maxHealth;
    }

    public function getCurrentHealth(): float
    {
        return $this->currentHealth;
    }

    public function setCurrentHealth(float $currentHealth): void
    {
        $this->currentHealth = $currentHealth;
    }

    public function getPercentage(): float
    {
        if (round($this->maxHealth, 4) === 0.0000) {
            return 0.0;
        }
        return round(bcdiv($this->currentHealth, $this->maxHealth, 4), 2);
    }

    public function isAlive(): bool
    {
        return $this->currentHealth > 0.0;
    }

    public static function getId(): string
    {
        return 'health_component';
    }
}