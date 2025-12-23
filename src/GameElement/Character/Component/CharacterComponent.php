<?php

namespace App\GameElement\Character\Component;

use App\GameElement\Core\GameComponent\GameComponentInterface;
use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class CharacterComponent implements GameComponentInterface
{
    public function __construct(
        protected float $maxHealth = 1.0,
        protected float $health = -1.0,
    ) {
        if ($health < 0) {
            $this->health = $maxHealth;
        }
    }

    public function getMaxHealth(): float
    {
        return $this->maxHealth;
    }

    public function setMaxHealth(float $maxHealth): void
    {
        $this->maxHealth = $maxHealth;
    }

    public function getHealth(): float
    {
        return $this->health;
    }

    public function setHealth(float $health): void
    {
        $this->health = $health;
    }

    public function getHealthPercentage(): float
    {
        if (round($this->maxHealth, 4) === 0.0000) {
            return 0.0;
        }
        return round(bcdiv($this->health, $this->maxHealth, 4), 2);
    }

    public function isAlive(): bool
    {
        return $this->health > 0.0;
    }


    public static function getId(): string
    {
        return 'character_component';
    }
}