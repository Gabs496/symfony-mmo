<?php

namespace App\GameElement\Item\Component;

use Attribute;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;
use PennyPHP\Core\Entity\GameComponent;

#[ORM\Entity]
#[Attribute]
#[UniqueConstraint(fields: ['bagId', 'gameObject'])]
class ItemInBagSlotComponent extends GameComponent
{
    #[ORM\Column]
    private int $maxQuantity;

    #[ORM\Column]
    private string $bagId;

    public function __construct(
        ItemBagComponent|string $bagComponent,
        #[ORM\Column]
        private int    $quantity = 1,
        ?int           $maxQuantity = null,

    ){
        $this->bagId = is_string($bagComponent) ? $bagComponent : $bagComponent->getGameObject()->getId();
        if ($maxQuantity === null) {
            $maxQuantity = $this->quantity;
        }
        $this->maxQuantity = $maxQuantity;
        parent::__construct();
    }

    public function getBagId(): string
    {
        return $this->bagId;
    }

    public function setBagId(string $bagId): void
    {
        $this->bagId = $bagId;
    }

    public function getItem(): ItemComponent
    {
        return $this->getGameObject()->getComponent(ItemComponent::class);
    }

    public function setItem(ItemComponent $item): void
    {
        $this->setGameObject($item->getGameObject());
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    public function getMaxQuantity(): int
    {
        return $this->maxQuantity;
    }

    public function setMaxQuantity(int $maxQuantity): void
    {
        $this->maxQuantity = $maxQuantity;
    }

    public function isFull(): bool
    {
        return $this->quantity >= $this->maxQuantity;
    }

    public function decreaseBy(int $quantity): void
    {
        $this->quantity -= $quantity;
    }

    public function increaseBy(int $quantity): void
    {
        $this->quantity += $quantity;
    }
}