<?php

namespace App\Entity\Data;

use App\Entity\Game\Item;
use App\Interface\ItemInterface;
use App\Interface\ItemTypeInterface;
use App\Repository\Data\ItemInstanceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ItemInstanceRepository::class)]
class ItemInstance implements ItemInterface
{
    #[ORM\Id]
    #[ORM\Column(type: 'guid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?string $id = null;

    #[ORM\ManyToOne(targetEntity: Item::class)]
    private Item $item;

    #[ORM\ManyToOne(targetEntity: ItemInstanceBag::class, inversedBy: 'items')]
    private ?ItemInstanceBag $bag = null;

    #[ORM\Column(type: 'integer')]
    private int $quantity = 1;

    #[ORM\Column(type: 'float')]
    private float $wear;

    public function __construct(Item $item)
    {
        $this->item = $item;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getItem(): Item
    {
        return $this->item;
    }

    public function getBag(): ?ItemInstanceBag
    {
        return $this->bag;
    }

    public function setBag(ItemInstanceBag $bag): static
    {
        $this->bag = $bag;
        return $this;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;
        return $this;
    }

    public function getWear(): float
    {
        return $this->wear;
    }

    public function setWear(float $wear): static
    {
        $this->wear = $wear;
        return $this;
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

    public function getAdvisedExperience(): float
    {
        return $this->item->getAdvisedExperience();
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

    public function getMaxCondition(): float
    {
        return $this->item->getMaxCondition();
    }

    public function getType(): ItemTypeInterface
    {
        return $this->item->getType();
    }

    public static function createFrom(Item $item, int $quantity = 1): ItemInstance
    {
        return (new self($item))
            ->setQuantity($quantity)
            ->setWear(1.0)
        ;

    }

    public function isInstanceOf(Item $item): bool
    {
        return $this->item === $item;
    }

    public function addQuantity(int $quantity): static
    {
        $this->quantity += $quantity;
        return $this;
    }
}
