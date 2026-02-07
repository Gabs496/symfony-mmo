<?php

namespace App\GameElement\Combat\Component;

use App\GameElement\Combat\Component\Stat\DefensiveStat;
use App\GameElement\Combat\Component\Stat\OffensiveStat;
use PennyPHP\Core\GameComponent\GameComponent;
use Attribute;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;

#[Attribute(Attribute::TARGET_CLASS)]
#[Entity]
class CombatComponent extends GameComponent
{
    public function __construct(
        /** @var AbstractStat[] $stats */
        #[Column(type: 'json_document', nullable: false)]
        protected array  $stats,
        #[Column(type: 'string', nullable: false)]
        protected string $managerId,
    )
    {
        parent::__construct();
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

    public static function getComponentName(): string
    {
        return 'combat_component';
    }
}