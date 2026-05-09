<?php

namespace App\GameElement\Crafting;

use PennyPHP\Core\Entity\GameObject;

readonly class CraftingIngredient
{

    public function __construct(
        private GameObject $gameObject,
        private int        $quantity,
    )
    {
    }

    public function getGameObject(): GameObject
    {
        return $this->gameObject;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }
}