<?php

namespace App\GameElement\Combat\Event;

use App\GameElement\Combat\Component\Attack;
use App\GameElement\Combat\Component\Damage;
use App\GameElement\Combat\Component\Defense;
use Symfony\Contracts\EventDispatcher\Event;

class CombatDamageCalculateEvent extends Event
{
    protected ?Damage $damage = null;

    public function __construct(
        protected Attack  $attack,
        protected Defense $defense,
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

    public function getDamage(): ?Damage
    {
        return $this->damage;
    }

    public function increaseDamage(float $variation): self
    {
        if (!$this->damage) {
            $this->damage = new Damage();
        }
        $this->damage->setValue($this->damage->getValue() + $variation);
        return $this;
    }
}