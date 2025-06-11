<?php

namespace App\GameElement\Mob;

use App\GameElement\Combat\Component\AbstractStat;
use App\GameElement\Combat\Component\Combat;
use App\GameElement\Combat\Component\Stat\DefensiveStat;
use App\GameElement\Combat\Component\Stat\OffensiveStat;
use App\GameElement\Core\GameComponent\GameComponentInterface;
use App\GameElement\Core\GameObject\AbstractGameObjectPrototype;
use App\GameElement\Health\Component\Health;
use App\GameElement\Mob\Combat\MobCombatManager;
use App\GameElement\Render\Component\Render;
use App\GameElement\Reward\RewardInterface;

abstract class AbstractMob extends AbstractGameObjectPrototype
{
    /** @var GameComponentInterface[] */
    protected array $components;

    public function __construct(
        string          $id,
        string          $name,
        string          $description,
        float           $maxHealth,
        /** @var AbstractStat[] $combatStats */
        array           $combatStats = [],
        /** @var RewardInterface[] */
        protected array $rewardOnDefeats = [],
        ?string         $iconPath = null,
        array           $components = [],
    )
    {
        $components = array_merge(
            $components,
            [
                new Render(
                    name: $name,
                    description: $description,
                    iconPath: $iconPath ?? '/mob/' . strtolower($id) . '.png',
                    template: 'Mob:MapRender'
                ),
                new Health($maxHealth, $maxHealth),
                //TODO: separate domains
                new Combat($combatStats, MobCombatManager::getId()),
            ],
        );
        parent::__construct($id, $components);
    }

    /** @return AbstractStat[] */
    public function getCombatStats(): array
    {
        return $this->getComponent(Combat::class)->getStats();
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