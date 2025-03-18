<?php

namespace App\GameObject\Item\Resource;

use App\GameElement\Item\AbstractItem;
use App\GameElement\Item\AvailableAction\Drop;

readonly abstract class AbstractBaseResource extends AbstractItem
{
    public function getAvailableActions(): array
    {
        return [
            new Drop(),
        ];
    }
}