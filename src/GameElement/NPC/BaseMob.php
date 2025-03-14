<?php

namespace App\GameElement\NPC;

use App\GameElement\Combat\Stats\BaseStat;
use App\GameElement\Combat\Stats\DefensiveStat;
use App\GameElement\Combat\Stats\OffensiveStat;
use App\GameElement\Core\GameObject\AbstractGameObject;

abstract readonly class BaseMob extends AbstractGameObject
{
    public function __construct(
        string $id,
        protected string $name,
        protected float $maxHealth,
        protected string $description,
    )
    {
        parent::__construct($id);
    }

    /** @return BaseStat[] */
    public abstract function getCombatStats(): array;

    public function getOffensiveStats(): array
    {
        return array_filter($this->getCombatStats(), fn(BaseStat $stat) => $stat instanceof OffensiveStat);
    }

    public function getDefensiveStats(): array
    {
        return array_filter($this->getCombatStats(), fn(BaseStat $stat) => !$stat instanceof DefensiveStat);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getMaxHealth(): float
    {
        return $this->maxHealth;
    }
}