<?php

namespace App\GameElement\Item\Component;

use Attribute;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\OneToMany;
use PennyPHP\Core\Entity\GameComponent;

#[Attribute(Attribute::TARGET_CLASS)]
#[Entity]
class ItemBagComponent extends GameComponent
{
    public function __construct(
        #[Column]
        protected float $maxSize,
        /** @var Collection<int, ItemBagSlot> */
        #[OneToMany(targetEntity: ItemBagSlot::class, mappedBy: "bag", orphanRemoval: true)]
        protected Collection $slots,

    )
    {
        parent::__construct();
    }

    public function getMaxSize(): int
    {
        return $this->maxSize;
    }

    /** @return Collection<int, ItemBagSlot> */
    public function getSlots(): Collection
    {
        return $this->slots;
    }

    public function setSlots(Collection $slots): void
    {
        $this->slots = $slots;
    }

    public function addSlot(ItemBagSlot $slot): static
    {
        $this->slots->add($slot);
        return $this;
    }

    public function removeSlot(ItemBagSlot $slot): static
    {
        $this->slots->removeElement($slot);
        return $this;
    }
}