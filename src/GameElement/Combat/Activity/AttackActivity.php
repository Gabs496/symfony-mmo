<?php

namespace App\GameElement\Combat\Activity;

use App\GameElement\Activity\Activity;
use App\GameElement\Activity\AbstractActivity;
use App\GameElement\Activity\ActivitySubjectTokenInterface;
use App\GameElement\Combat\CombatOpponentTokenInterface;
use App\GameElement\Combat\Phase\PreCalculatedAttack;

#[Activity(id: 'COMBAT')]
class AttackActivity extends AbstractActivity
{
    public function __construct(
        ActivitySubjectTokenInterface                 $subject,
        private readonly CombatOpponentTokenInterface $attacker,
        private readonly CombatOpponentTokenInterface $opponent,
        private readonly ?PreCalculatedAttack         $preCalculatedAttack = null,
    )
    {
        parent::__construct($subject);
    }

    public function getAttacker(): CombatOpponentTokenInterface
    {
        return $this->attacker;
    }

    public function getPreCalculatedAttack(): PreCalculatedAttack
    {
        return $this->preCalculatedAttack;
    }

    public function getOpponent(): CombatOpponentTokenInterface
    {
        return $this->opponent;
    }
}