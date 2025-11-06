<?php

namespace App\GameElement\Gathering\Interaction;

use App\GameElement\Interaction\AbstractInteraction;

class GatherInteraction extends AbstractInteraction
{
    public function __construct(string $action)
    {
        parent::__construct('Gather', $action);
    }
}