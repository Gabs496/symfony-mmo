<?php

namespace App\Engine\Item\Action;

readonly class Unequip extends AbstractAvailableAction
{
    public function __construct(
        string $verb = 'unequip',
        string $description = 'Unequip this item',
    ){
        parent::__construct($verb, $description);
    }
}