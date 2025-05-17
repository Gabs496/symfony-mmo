<?php

namespace App\GameElement\Mob;

use App\GameElement\Combat\Stats\AbstractStat;
use App\GameElement\Combat\Stats\DefensiveStat;
use App\GameElement\Combat\Stats\OffensiveStat;
use App\GameElement\Core\GameComponent\AbstractGameComponent;
use App\GameElement\Core\GameObject\AbstractGameObject;
use App\GameElement\Health\Component\Health;
use App\GameElement\Reward\RewardInterface;

abstract readonly class AbstractMob extends AbstractGameObject
{
    /** @var AbstractGameComponent[] */
    protected array $components;

    public function __construct(
        string $id,
        protected string $name,
        float $maxHealth,
        protected string $description,
        /** @var AbstractStat[] */
        protected array $combatStats = [],
        /** @var RewardInterface[] */
        protected array $rewardOnDefeats = [],
        array $components = [],
    )
    {
        $components = array_merge($components,[
            new Health($maxHealth, $maxHealth)
        ]);
        parent::__construct($id, $components);
    }

    /** @return AbstractStat[] */
    public function getCombatStats(): array
    {
        return $this->combatStats;
    }

    /** @return RewardInterface[] */
    public function getRewardOnDefeats(): array
    {
        return $this->rewardOnDefeats;
    }

    /** @return OffensiveStat[] */
    public function getOffensiveStats(): array
    {
        return array_filter($this->getCombatStats(), fn(AbstractStat $stat) => $stat instanceof OffensiveStat);
    }

    public function getDefensiveStats(): array
    {
        return array_filter($this->getCombatStats(), fn(AbstractStat $stat) => !$stat instanceof DefensiveStat);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getIcon(): string
    {
        return '/mob/' . strtolower($this->id) . '.png';
    }
}