<?php

namespace App\GameElement\Equipment\Interaction;

use App\GameElement\Interaction\AbstractInteraction;
use App\GameElement\Interaction\Action;

class UnequipInteraction extends AbstractInteraction
{
    public function __construct(Action $action)
    {
        parent::__construct("Unequip", $action);
    }
}