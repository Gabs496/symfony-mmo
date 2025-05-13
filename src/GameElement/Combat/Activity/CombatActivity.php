<?php

namespace App\GameElement\Combat\Activity;

use App\GameElement\Activity\Activity;
use App\GameElement\Activity\AbstractActivity;
use App\GameElement\Combat\CombatOpponentInterface;

#[Activity(id: 'COMBAT')]
class CombatActivity extends AbstractActivity
{
    public function __construct(
        private readonly CombatOpponentInterface $firstOpponent,
        private readonly CombatOpponentInterface $secondOpponent,
    )
    {
    }

    public function getFirstOpponent(): CombatOpponentInterface
    {
        return $this->firstOpponent;
    }

    public function getSecondOpponent(): CombatOpponentInterface
    {
        return $this->secondOpponent;
    }
}