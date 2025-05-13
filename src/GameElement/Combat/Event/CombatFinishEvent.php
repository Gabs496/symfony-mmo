<?php

namespace App\GameElement\Combat\Event;

use App\GameElement\Combat\CombatOpponentInterface;
use Symfony\Contracts\EventDispatcher\Event;

class CombatFinishEvent extends Event
{
    public function __construct(
        private readonly CombatOpponentInterface $winner,
        private readonly CombatOpponentInterface $loser,
    )
    {
    }

    public function getWinner(): CombatOpponentInterface
    {
        return $this->winner;
    }

    public function getLoser(): CombatOpponentInterface
    {
        return $this->loser;
    }
}