<?php

namespace App\GameElement\Combat;

use App\GameElement\Combat\Stats\BaseStat;
use InvalidArgumentException;

class StatCollection
{
    public function __construct(
        /** @var BaseStat[] */
        private array $stats = []
    )
    {
    }

    public function increase(string $statId, float $variation): self
    {
        $stat = $this->getStat($statId);
        $newValue = (float)bcadd($stat->getValue(), $variation, 2);
        $this->insertOrReplace($statId, $newValue);

        return $this;
    }

    public function insertOrReplace(string $statId, float $value): self
    {
        $newStat = new $statId(max($value, 0.0));

        if (!$newStat instanceof BaseStat) {
            throw new InvalidArgumentException('Invalid stat class: ' . $statId);
        }

        foreach ($this->stats as $i => $stat) {
            if ($stat::class === $statId) {
                $this->stats[$i] = $newStat;
                return $this;
            }
        }

        $this->stats[] = $newStat;

        return $this;
    }

    public function getStat(string $statId): BaseStat
    {
        foreach ($this->stats as $baseStat) {
            if ($baseStat::class === $statId) {
                return $baseStat;
            }
        }

        $this->insertOrReplace($statId, 0.0);
        return $this->getStat($statId);
    }

    public function getStats(): array
    {
        return $this->stats;
    }
}