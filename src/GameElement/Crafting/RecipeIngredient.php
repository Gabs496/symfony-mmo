<?php

namespace App\GameElement\Crafting;

use App\GameElement\Item\AbstractItem;

readonly class RecipeIngredient
{
    public function __construct(
        private AbstractItem $item,
        private int          $quantity
    )
    {
    }

    public function getItem(): AbstractItem
    {
        return $this->item;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }
}