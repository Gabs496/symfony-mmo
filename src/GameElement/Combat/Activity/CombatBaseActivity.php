<?php

namespace App\GameElement\Combat\Activity;

use App\GameElement\Activity\Activity;
use App\GameElement\Activity\BaseActivity;

#[Activity(id: 'COMBAT')]
class CombatBaseActivity extends BaseActivity
{
    public function __construct(
        private readonly string $firstOpponent,
        private readonly string $secondOpponent,
    )
    {
    }

    public function getFirstOpponent(): string
    {
        return $this->firstOpponent;
    }

    public function getSecondOpponent(): string
    {
        return $this->secondOpponent;
    }
}