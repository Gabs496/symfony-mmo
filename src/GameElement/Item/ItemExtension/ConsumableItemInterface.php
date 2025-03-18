<?php

namespace App\GameElement\Item\ItemExtension;

interface ConsumableItemInterface
{
    public function getMaxCondition(): float;
}