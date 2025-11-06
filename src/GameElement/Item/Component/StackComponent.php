<?php

namespace App\GameElement\Item\Component;

use App\GameElement\Core\GameComponent\GameComponentInterface;

class StackComponent implements GameComponentInterface
{
    private int $maxQuantity;

    public function __construct(
        private int $currentQuantity = 0,
        ?int $maxQuantity = null
    ){
        if ($maxQuantity === null) {
            $maxQuantity = $this->currentQuantity;
        }
        $this->maxQuantity = $maxQuantity;
    }

    public function getCurrentQuantity(): int
    {
        return $this->currentQuantity;
    }

    public function setCurrentQuantity(int $currentQuantity): void
    {
        $this->currentQuantity = $currentQuantity;
    }

    public function getMaxQuantity(): int
    {
        return $this->maxQuantity;
    }

    public function decreaseBy(int $quantity): void
    {
        $this->currentQuantity -= $quantity;
    }

    public function increaseBy(int $quantity): void
    {
        $this->currentQuantity += $quantity;
    }

    public function isFull(): bool
    {
        return $this->currentQuantity >= $this->maxQuantity;
    }

    public static function getId(): string
    {
        return 'stack_component';
    }
}