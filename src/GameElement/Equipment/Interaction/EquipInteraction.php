<?php

namespace App\GameElement\Equipment\Interaction;

use App\GameElement\Interaction\AbstractInteraction;
use App\GameElement\Interaction\Action;

class EquipInteraction extends AbstractInteraction
{
    public function __construct(Action $action)
    {
        parent::__construct("Equip", $action);
    }
}