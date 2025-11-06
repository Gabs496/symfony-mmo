<?php

namespace App\GameElement\Item\Interaction;

use App\GameElement\Interaction\AbstractInteraction;

class EatInteraction extends AbstractInteraction
{
    public function __construct(string $action)
    {
        parent::__construct('Eat', $action);
    }
}