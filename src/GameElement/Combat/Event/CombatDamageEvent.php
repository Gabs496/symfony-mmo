<?php

namespace App\GameElement\Combat\Event;

use App\GameElement\Combat\Phase\Attack;
use App\GameElement\Combat\Phase\Damage;
use App\GameElement\Combat\Phase\Defense;
use Symfony\Contracts\EventDispatcher\Event;

class CombatDamageEvent extends Event
{
    public function __construct(
        private readonly Attack $attack,
        private readonly Defense $defense,
        private readonly Damage $damage,
    )
    {
    }

    public function getAttack(): Attack
    {
        return $this->attack;
    }

    public function getDefense(): Defense
    {
        return $this->defense;
    }

    public function getDamage(): Damage
    {
        return $this->damage;
    }
}