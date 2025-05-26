<?php

namespace App\GameElement\Mob;

use App\GameElement\Combat\Component\AbstractStat;
use App\GameElement\Combat\Component\Combat;
use App\GameElement\Combat\Component\Stat\DefensiveStat;
use App\GameElement\Combat\Component\Stat\OffensiveStat;
use App\GameElement\Core\GameComponent\GameComponentInterface;
use App\GameElement\Core\GameObject\AbstractGameObject;
use App\GameElement\Health\Component\Health;

abstract class AbstractMob extends AbstractGameObject
{
    /** @var GameComponentInterface[] */
    protected array $components;

    public function __construct(
        string           $id,
        protected string $name,
        protected string $description,
        float            $maxHealth,
        /** @var AbstractStat[] $combatStats */
        array  $combatStats = [],
        array  $onDefeats = [],
        array  $components = [],
    )
    {
        $components = array_merge(
            $components,
            [
                new Health($maxHealth, $maxHealth),
                new Combat($combatStats, $onDefeats),
            ],
        );
        parent::__construct($id, $components);
    }

    /** @return AbstractStat[] */
    public function getCombatStats(): array
    {
        return $this->getComponent(Combat::class)->getStats();
    }

    public function getOnDefeats(): array
    {
        return $this->getComponent(Combat::class)->getOnDefeats();
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