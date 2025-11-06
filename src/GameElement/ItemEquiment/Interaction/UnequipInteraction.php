<?php

namespace App\GameElement\ItemEquiment\Interaction;

use App\GameElement\Interaction\AbstractInteraction;

class UnequipInteraction extends AbstractInteraction
{
    public function __construct(string $action)
    {
        parent::__construct("Unequip", $action);
    }
}