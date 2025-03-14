<?php

namespace App\GameElement\Combat\Event;

use App\GameElement\Combat\Exception\DamageNotCalculatedException;
use App\GameElement\Combat\StatCollection;
use Symfony\Contracts\EventDispatcher\Event;

class CombatDamageCalculateEvent extends Event
{
    protected ?float $damage = null;

    public function __construct(
        protected StatCollection $offensiveStats,
        protected StatCollection $defensiveStats,
    )
    {
    }

    public function getOffensiveStats(): StatCollection
    {
        return $this->offensiveStats;
    }

    public function getDefensiveStats(): StatCollection
    {
        return $this->defensiveStats;
    }

    /**
     * @throws DamageNotCalculatedException
     */
    public function getDamage(): float
    {
        if ($this->damage === null) {
            throw new DamageNotCalculatedException(sprintf("Damage not calculated: check if %s event has been listened", self::class));
        }

        return $this->damage;
    }

    public function increaseDamage(float $variation): self
    {
        $this->damage = bcadd($this->damage, $variation, 2);
        return $this;
    }
}