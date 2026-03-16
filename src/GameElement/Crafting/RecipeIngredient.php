<?php

namespace App\GameElement\Crafting;


use PennyPHP\Core\GameObjectPrototypeInterface;

readonly class RecipeIngredient
{
    public function __construct(
        private GameObjectPrototypeInterface $prototype,
        private int    $quantity
    )
    {
    }

    public function getPrototype(): GameObjectPrototypeInterface
    {
        return $this->prototype;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }
}