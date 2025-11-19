<?php

namespace App\GameElement\Gathering\Interaction;

use App\GameElement\Interaction\AbstractInteraction;
use App\GameElement\Interaction\Action;

class GatherInteraction extends AbstractInteraction
{
    public function __construct(Action $action)
    {
        parent::__construct('Gather', $action);
    }
}