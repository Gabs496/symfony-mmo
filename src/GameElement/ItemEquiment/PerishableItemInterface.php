<?php

namespace App\GameElement\ItemEquiment;

interface PerishableItemInterface
{
    public function getMaxCondition(): float;
}