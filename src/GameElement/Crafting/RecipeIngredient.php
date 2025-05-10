<?php

namespace App\GameElement\Crafting;

use App\GameElement\Item\AbstractItemPrototype;

readonly class RecipeIngredient
{
    public function __construct(
        private AbstractItemPrototype $item,
        private int                   $quantity
    )
    {
    }

    public function getItem(): AbstractItemPrototype
    {
        return $this->item;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }
}