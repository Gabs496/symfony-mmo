<?php

namespace App\GameElement\Health\Component;

use App\GameElement\Core\GameComponent\AbstractGameComponent;
use App\GameElement\Core\GameObject\GameObjectInterface;

class Health extends AbstractGameComponent
{
    public function __construct(
        GameObjectInterface $gameObject,
        protected float $maxHealth,
        protected float $currentHealth,

    ) {
        parent::__construct($gameObject);
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
        return round(bcdiv($this->currentHealth, $this->maxHealth, 4), 2);
    }
}