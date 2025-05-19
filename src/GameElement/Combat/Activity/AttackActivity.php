<?php

namespace App\GameElement\Combat\Activity;

use App\GameElement\Activity\Activity;
use App\GameElement\Activity\AbstractActivity;
use App\GameElement\Activity\ActivitySubjectTokenInterface;
use App\GameElement\Combat\CombatOpponentTokenInterface;
use App\GameElement\Combat\Component\Attack;

#[Activity(id: 'COMBAT')]
class AttackActivity extends AbstractActivity
{
    public function __construct(
        ActivitySubjectTokenInterface $subject,
        private readonly Attack $attack,
        private readonly CombatOpponentTokenInterface $opponent,
    )
    {
        parent::__construct($subject);
    }

    public function getAttack(): Attack
    {
        return $this->attack;
    }

    public function getOpponent(): CombatOpponentTokenInterface
    {
        return $this->opponent;
    }
}