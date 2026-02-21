<?php

namespace App\GameElement\Item\Component;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Symfony\Component\Uid\Uuid;

#[Entity]
#[UniqueConstraint(fields: ['itemBagComponent', 'item'])]
class ItemBagSlot
{
    #[ORM\Id]
    #[Column(type: "guid")]
    private string $id;
    #[Column]
    private int $maxQuantity;
    public function __construct(
        #[ManyToOne(targetEntity: ItemBagComponent::class, inversedBy: "slots")]
        private ItemBagComponent $bag,
        #[OneToOne(ItemBagComponent::class)]
        private ItemComponent    $item,
        #[Column]
        private int              $quantity = 1,
        ?int $maxQuantity = null,

    ){
        $this->id = Uuid::v7();
        if ($maxQuantity === null) {
            $maxQuantity = $this->quantity;
        }
        $this->maxQuantity = $maxQuantity;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getBag(): ItemBagComponent
    {
        return $this->bag;
    }

    public function setBag(ItemBagComponent $bag): void
    {
        $this->bag = $bag;
    }

    public function getItem(): ItemComponent
    {
        return $this->item;
    }

    public function setItem(ItemComponent $item): void
    {
        $this->item = $item;
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