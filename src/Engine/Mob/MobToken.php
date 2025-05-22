<?php

namespace App\Engine\Mob;

use App\Entity\Game\MapSpawnedMob;
use App\GameElement\Combat\CombatOpponentTokenInterface;

class MobToken implements CombatOpponentTokenInterface
{
    public function __construct(
        protected string $id,
    )
    {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getCombatOpponentClass(): string
    {
        return MapSpawnedMob::class;
    }
}