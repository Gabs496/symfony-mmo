<?php

namespace App\Entity;

use App\Entity\Interface\ItemInterface;
use App\Entity\Interface\ItemTypeInterface;
use App\Repository\ItemInstanceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ItemInstanceRepository::class)]
class ItemInstance implements ItemInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[ORM\Column(type: 'guid')]
    private ?string $id = null;

    #[ORM\ManyToOne(targetEntity: Item::class)]
    private Item $item;

    #[ORM\ManyToOne(targetEntity: ItemInstanceBag::class, inversedBy: 'items')]
    private ItemInstanceBag $bag;

    #[ORM\Column(type: 'integer')]
    private int $quantity = 1;

    #[ORM\Column(type: 'float')]
    private float $condition;

    public function __construct(Item $item,ItemInstanceBag $bag)
    {
        $this->item = $item;
        $this->bag = $bag;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getBag(): ItemInstanceBag
    {
        return $this->bag;
    }

    public function setBag(ItemInstanceBag $bag): void
    {
        $this->bag = $bag;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    public function getName(): string
    {
        return $this->item->getName();
    }

    public function getDescription(): string
    {
        return $this->item->getDescription();
    }

    public function getWeight(): float
    {
        return $this->item->getWeight();
    }

    public function getMinExperienceRequired(): float
    {
        return $this->item->getMinExperienceRequired();
    }

    public function isEquippable(): bool
    {
        return $this->item->isEquippable();
    }

    public function isConsumable(): bool
    {
        return $this->item->isConsumable();
    }

    public function isStackable(): bool
    {
        return $this->item->isStackable();
    }

    public function getMaxDurability(): float
    {
        return $this->item->getMaxDurability();
    }

    public function getType(): ItemTypeInterface
    {
        return $this->item->getType();
    }
}
