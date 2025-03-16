<?php

namespace App\GameElement\Combat\Event;

use App\GameElement\Combat\StatCollection;
use App\GameElement\Combat\Stats\AbstractStat;
use Symfony\Contracts\EventDispatcher\Event;

abstract class CombatCalculateStatsEvent extends Event
{
    private StatCollection $stats;

    public function __construct(
    )
    {
        $this->stats = new StatCollection();
    }

    public function increase(string $statId, float $variation): self
    {
       $this->stats->increase($statId, $variation);
       return $this;
    }

    public function insertOrReplace(string $statId, float $value): self
    {
        $this->stats->insertOrReplace($statId, $value);
        return $this;
    }

    public function getStat(string $statId): AbstractStat
    {
        return $this->stats->getStat($statId);
    }

    public function getStats(): StatCollection
    {
        return $this->stats;
    }
}