<?php

namespace App\Engine\MapMob;

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
}