<?php

namespace App\Engine\Item\Action;

readonly class Equip extends AbstractAvailableAction
{
    public function __construct(
        string $verb = 'equip',
        string $description = 'Equip this item',
    ){
        parent::__construct($verb, $description);
    }
}