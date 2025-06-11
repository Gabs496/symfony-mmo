<?php

namespace App\Engine\Item\Action;

readonly class Eat extends AbstractAvailableAction
{
    public function __construct(
        string $verb = 'eat',
        string $description = 'Eat',
    ){
        parent::__construct($verb, $description);
    }
}