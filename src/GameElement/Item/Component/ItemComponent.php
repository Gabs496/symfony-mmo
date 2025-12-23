<?php

namespace App\GameElement\Item\Component;

use App\GameElement\Core\GameComponent\GameComponentInterface;
use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class ItemComponent implements GameComponentInterface
{
    public function __construct(
        private float $weight = 0.0,
        private int $maxStackSize = 99,
        private int $quantity = 0,
    )
    {
        if ($quantity <= 0) {
            $this->quantity = 1;
        }
    }

    public function getWeight(): float
    {
        return $this->weight;
    }

    public function setWeight(float $weight): void
    {
        $this->weight = $weight;
    }

    public function getMaxStackSize(): int
    {
        return $this->maxStackSize;
    }

    public function setMaxStackSize(int $maxStackSize): void
    {
        $this->maxStackSize = $maxStackSize;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    public function isStackFull(): bool
    {
        return $this->quantity >= $this->maxStackSize;
    }

    public function decreaseBy(int $quantity): void
    {
        $this->quantity -= $quantity;
    }

    public function increaseBy(int $quantity): void
    {
        $this->quantity += $quantity;
    }

    public static function getId(): string
    {
        return 'item_weight_component';
    }
}