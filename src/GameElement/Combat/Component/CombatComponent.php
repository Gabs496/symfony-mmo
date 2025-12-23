<?php

namespace App\GameElement\Combat\Component;

use App\GameElement\Combat\Component\Stat\DefensiveStat;
use App\GameElement\Combat\Component\Stat\OffensiveStat;
use App\GameElement\Core\GameComponent\GameComponentInterface;
use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
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

    /** @return OffensiveStat[] */
    public function getOffensiveStats(): array
    {
        return array_filter($this->stats, fn(AbstractStat $stat) => $stat instanceof OffensiveStat);
    }

    /** @return DefensiveStat[] */
    public function getDefensiveStats(): array
    {
        return array_filter($this->stats, fn(AbstractStat $stat) => !$stat instanceof OffensiveStat);
    }

    public function getStatByClass(string $statClass): ?AbstractStat
    {
        foreach ($this->stats as $stat) {
            if ($stat instanceof $statClass) {
                return $stat;
            }
        }

        return null;
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