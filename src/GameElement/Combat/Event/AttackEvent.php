<?php

namespace App\GameElement\Combat\Event;

use App\GameElement\Combat\Phase\Attack;
use App\GameElement\Core\GameObject\GameObjectInterface;

readonly class AttackEvent
{
    public function __construct(
        protected Attack                      $attack,
        protected GameObjectInterface $defender,
    ){
    }

    public function getAttack(): Attack
    {
        return $this->attack;
    }

    public function getDefender(): GameObjectInterface
    {
        return $this->defender;
    }
}