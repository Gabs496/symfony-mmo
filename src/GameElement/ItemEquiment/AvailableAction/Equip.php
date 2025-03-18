<?php

namespace App\GameElement\ItemEquiment\AvailableAction;

use App\GameElement\Item\AvailableAction\AbstractAvailableAction;

readonly class Equip extends AbstractAvailableAction
{
    public function __construct(
        string $verb = 'equip',
        string $description = 'Equip this item',
    ){
        parent::__construct($verb, $description);
    }
}