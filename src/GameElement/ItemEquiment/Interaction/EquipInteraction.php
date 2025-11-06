<?php

namespace App\GameElement\ItemEquiment\Interaction;

use App\GameElement\Interaction\AbstractInteraction;

class EquipInteraction extends AbstractInteraction
{
    public function __construct(string $action)
    {
        parent::__construct("Equip", $action);
    }
}