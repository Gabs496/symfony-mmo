<?php

namespace App\GameElement\Combat\Event;

use Symfony\Contracts\EventDispatcher\Event;

class CombatFinishEvent extends Event
{
    public function __construct(
        private readonly object $winner,
        private readonly object $looser,
    )
    {
    }

    public function getWinner(): object
    {
        return $this->winner;
    }

    public function getLoser(): object
    {
        return $this->looser;
    }
}