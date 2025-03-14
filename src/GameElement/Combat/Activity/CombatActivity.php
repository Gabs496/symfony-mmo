<?php

namespace App\GameElement\Combat\Activity;

use App\GameElement\Activity\Activity;
use App\GameElement\Activity\BaseActivity;

#[Activity(id: 'COMBAT')]
class CombatActivity extends BaseActivity
{
    public function __construct(
        private readonly object $firstOpponent,
        private readonly object $secondOpponent,
    )
    {
    }

    public function getFirstOpponent(): object
    {
        return $this->firstOpponent;
    }

    public function getSecondOpponent(): object
    {
        return $this->secondOpponent;
    }
}