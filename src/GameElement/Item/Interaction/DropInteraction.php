<?php

namespace App\GameElement\Item\Interaction;

use App\GameElement\Interaction\AbstractInteraction;

class DropInteraction extends AbstractInteraction
{
    public function __construct(string $action)
    {
        parent::__construct('Drop', $action);
    }
}