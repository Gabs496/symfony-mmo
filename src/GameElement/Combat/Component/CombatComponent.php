<?php

namespace App\GameElement\Combat\Component;

use App\GameElement\Combat\Component\Stat\OffensiveStat;
use App\GameElement\Core\GameComponent\GameComponentInterface;

readonly class CombatComponent implements GameComponentInterface
{
    public function __construct(
        /** @var AbstractStat[] $stats */
        protected array  $stats,
        protected string $managerId,
    )
    {
    }

    public function getStats(): array
    {
        return $this->stats;
    }

    public function getOffensiveStats(): array
    {
        return array_filter($this->stats, fn(AbstractStat $stat) => $stat instanceof OffensiveStat);
    }

    public function getDefensiveStats(): array
    {
        return array_filter($this->stats, fn(AbstractStat $stat) => !$stat instanceof OffensiveStat);
    }

    public function getManagerId(): string
    {
        return $this->managerId;
    }

    public static function getId(): string
    {
        return 'combat_component';
    }
}