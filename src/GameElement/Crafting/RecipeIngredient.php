<?php

namespace App\GameElement\Crafting;

readonly class RecipeIngredient
{
    public function __construct(
        private string $itemId,
        private int $quantity
    )
    {
    }

    public function getItemId(): string
    {
        return $this->itemId;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }
}