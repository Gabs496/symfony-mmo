<?php

namespace App\GameElement\Combat\Interaction;

use App\GameElement\Interaction\AbstractInteraction;

class FightInteraction extends AbstractInteraction
{
    public function __construct(string $action)
    {
        parent::__construct('Fight!', $action);
    }
}