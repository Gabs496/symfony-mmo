<?php

namespace App\GameElement\Item\Interaction;

use App\GameElement\Interaction\AbstractInteraction;
use App\GameElement\Interaction\Action;

class EatInteraction extends AbstractInteraction
{
    public function __construct(Action $action)
    {
        parent::__construct('Eat', $action);
    }
}