<?php

namespace App\GameElement\Mob;

use App\GameElement\Combat\Component\Combat;
use App\GameElement\Combat\HasCombatComponentInterface;
use App\GameElement\Combat\Component\Stat\OffensiveStat;
use App\GameElement\Core\GameComponent\GameComponentInterface;
use App\GameElement\Core\GameComponent\GameComponentOwnerTrait;
use App\GameElement\Core\GameObject\GameObjectInterface;
use App\GameElement\Health\Component\Health;
use App\GameElement\Health\HasHealthComponentInterface;

abstract class AbstractMobInstance implements GameObjectInterface, HasHealthComponentInterface, HasCombatComponentInterface
{
    use GameComponentOwnerTrait;
    public function __construct(
        protected AbstractMob $mob,
        /** @var GameComponentInterface[] */
        protected array $components = [],
    )
    {
    }

    public function getMob(): AbstractMob
    {
        return $this->mob;
    }

    /** @return OffensiveStat[] */
    public function getOffensiveStats(): array
    {
        return $this->getComponent(Combat::class)->getOffensiveStats();
    }

    public function getDefensiveStats(): array
    {
        return $this->getComponent(Combat::class)->getDefensiveStats();
    }

    public function getHealth(): Health
    {
        return $this->getComponent(Health::class);
    }
}