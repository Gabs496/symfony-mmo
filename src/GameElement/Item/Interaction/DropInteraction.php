<?php

namespace App\GameElement\Item\Interaction;

use App\GameElement\Interaction\AbstractInteraction;
use App\GameElement\Interaction\Action;

class DropInteraction extends AbstractInteraction
{
    public function __construct(Action $action)
    {
        parent::__construct('Drop', $action);
    }
}