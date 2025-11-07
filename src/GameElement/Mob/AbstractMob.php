<?php

namespace App\GameElement\Mob;

use App\GameElement\Combat\Component\AbstractStat;
use App\GameElement\Combat\Component\CombatComponent;
use App\GameElement\Combat\Component\Stat\DefensiveStat;
use App\GameElement\Combat\Component\Stat\OffensiveStat;
use App\GameElement\Core\GameComponent\GameComponentInterface;
use App\GameElement\Core\GameObject\AbstractGameObjectPrototype;
use App\GameElement\Health\Component\HealthComponent;
use App\GameElement\Mob\Combat\MobCombatManager;
use App\GameElement\Reward\RewardInterface;

abstract class AbstractMob extends AbstractGameObjectPrototype
{
    /** @var GameComponentInterface[] */
    protected array $components;

    public function __construct(
        string          $id,
        float           $maxHealth,
        /** @var AbstractStat[] $combatStats */
        array           $combatStats = [],
        /** @var RewardInterface[] */
        protected array $rewardOnDefeats = [],
        array           $components = [],
    )
    {
        $components = array_merge([
                new HealthComponent($maxHealth, $maxHealth),
                new CombatComponent($combatStats, MobCombatManager::getId()),
            ], $components
        );
        parent::__construct($id, $components);
    }

    /** @return AbstractStat[] */
    public function getCombatStats(): array
    {
        return $this->getComponent(CombatComponent::class)->getStats();
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
}