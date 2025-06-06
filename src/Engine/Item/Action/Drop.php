<?php

namespace App\Engine\Item\Action;

readonly class Drop extends AbstractAvailableAction
{
    public function __construct(
        string $verb = 'drop',
        string $description = 'Drop this item',
    )
    {
        parent::__construct($verb, $description);
    }
}