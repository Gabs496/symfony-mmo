<?php

namespace App\GameElement\Crafting;


readonly class RecipeIngredient
{
    public function __construct(
        private string $itemPrototypeId,
        private int    $quantity
    )
    {
    }

    public function getItemPrototypeId(): string
    {
        return $this->itemPrototypeId;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }
}