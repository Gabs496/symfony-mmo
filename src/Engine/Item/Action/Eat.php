<?php

namespace App\Engine\Item\Action;

readonly class Heal extends AbstractAvailableAction
{
    public function __construct(
        string $verb = 'eat',
        string $description = 'Eat',
    ){
        parent::__construct($verb, $description);
    }
}