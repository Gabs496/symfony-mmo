<?php

namespace App\GameElement\Item\Component;

use App\GameElement\Core\GameComponent\GameComponent;
use Attribute;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;

#[Attribute(Attribute::TARGET_CLASS)]
#[Entity]
class ItemComponent extends GameComponent
{
    public function __construct(
        #[Column]
        private float            $weight = 0.0,
        #[Column]
        private int              $maxStackSize = 99,
        #[Column]
        private int              $quantity = 0,
    )
    {
        if ($quantity <= 0) {
            $this->quantity = 1;
        }
        parent::__construct();
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

    public static function getComponentName(): string
    {
        return 'item_weight_component';
    }
}