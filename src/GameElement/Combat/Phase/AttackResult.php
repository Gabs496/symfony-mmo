<?php

namespace App\GameElement\Combat\Phase;

class AttackResult
{
    public function __construct(
        protected Attack $attack,
        protected Defense $defense,
        protected Damage $damage,
        protected bool $isDefeated = false,
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

    public function setDamage(Damage $damage): void
    {
        $this->damage = $damage;
    }

    public function isDefeated(): bool
    {
        return $this->isDefeated;
    }

    public function setIsDefeated(bool $isDefeated): void
    {
        $this->isDefeated = $isDefeated;
    }
}