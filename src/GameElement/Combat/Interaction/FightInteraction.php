<?php

namespace App\GameElement\Combat\Interaction;

use App\GameElement\Interaction\AbstractInteraction;
use App\GameElement\Interaction\Action;

class FightInteraction extends AbstractInteraction
{
    public function __construct(Action $action)
    {
        parent::__construct('Fight!', $action);
    }
}