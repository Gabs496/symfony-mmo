<?php

namespace App\GameElement\Mob;

use App\GameElement\Combat\Stats\OffensiveStat;
use App\GameElement\Core\GameComponent\AbstractGameComponent;
use App\GameElement\Core\GameComponent\GameComponentOwnerTrait;
use App\GameElement\Core\GameObject\GameObjectInterface;
use App\GameElement\Health\Component\Health;
use App\GameElement\Health\HasHealthComponentInterface;

abstract class AbstractMobInstance implements GameObjectInterface, HasHealthComponentInterface
{
    use GameComponentOwnerTrait;
    public function __construct(
        protected AbstractMob $mob,
        /** @var AbstractGameComponent[] */
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
        return $this->getMob()->getOffensiveStats();
    }

    public function getDefensiveStats(): array
    {
        return $this->getMob()->getDefensiveStats();
    }

    public function getHealth(): Health
    {
        return $this->getComponent(Health::class);
    }
}