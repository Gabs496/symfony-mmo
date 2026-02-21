<?php

namespace App\GameElement\Item\Component;

use Attribute;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\OneToOne;
use PennyPHP\Core\Entity\GameComponent;

#[Attribute(Attribute::TARGET_CLASS)]
#[Entity]
class ItemComponent extends GameComponent
{
    public function __construct(
        #[Column]
        private float        $weight = 0.0,
        #[Column]
        private int          $maxStackSize = 99,
        /** @deprecated  */
        int                  $quantity = 0,
        #[OneToOne(ItemBagSlot::class, mappedBy: "item", orphanRemoval: true)]
        private ?ItemBagSlot $slot = null,
    )
    {
        if ($quantity !== null && $this->slot) {
            $this->setQuantity($quantity);
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

    public function getSlot(): ?ItemBagSlot
    {
        return $this->slot;
    }

    public function setSlot(?ItemBagSlot $slot): void
    {
        $this->slot = $slot;
    }

    /** @deprecated */
    public function getQuantity(): int
    {
        return $this->slot?->getQuantity() ?? 0;
    }

    /** @deprecated */
    public function setQuantity(int $quantity): void
    {
        $this->slot->setQuantity($quantity);
    }

    /** @deprecated  */
    public function isStackFull(): bool
    {
        return $this->slot?->isFull() ?? false;
    }

    /** @deprecated  */
    public function decreaseBy(int $quantity): void
    {
        $this->slot?->decreaseBy($quantity);
    }

    /** @deprecated  */
    public function increaseBy(int $quantity): void
    {
        $this->slot?->increaseBy($quantity);
    }

    public static function getComponentName(): string
    {
        return 'item_weight_component';
    }
}