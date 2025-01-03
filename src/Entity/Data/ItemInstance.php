<?php

namespace App\Entity\Data;

use App\Core\GameObject\GameObjectReference;
use App\GameElement\Item\AbstractItem;
use App\GameElement\Item\AbstractItemInstance;
use App\Repository\Data\ItemInstanceRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: ItemInstanceRepository::class)]
#[Broadcast(topics: ['@="item_bag_" ~ entity.getBag().getId()'], private: true, template: 'item_bag/ItemInstance.stream.html.twig')]
class ItemInstance extends AbstractItemInstance
{
    #[ORM\Id]
    #[ORM\Column(type: 'guid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?string $id = null;

    #[ORM\Column(length: 50)]
    private string $itemId;

    #[ORM\ManyToOne(targetEntity: ItemBag::class, inversedBy: 'items')]
    private ?ItemBag $bag = null;

    #[ORM\Column(type: 'integer')]
    private int $quantity = 1;

    #[ORM\Column(type: 'float')]
    private float $wear;

    #[GameObjectReference(AbstractItem::class, objectIdProperty: 'itemId')]
    protected readonly AbstractItem $item;

    public function __construct(AbstractItem $item)
    {
        parent::__construct($item);
        $this->itemId = $item->getId();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getItemId(): string
    {
        return $this->itemId;
    }

    public function getBag(): ?ItemBag
    {
        return $this->bag;
    }

    public function setBag(ItemBag $bag): static
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

    public static function createFrom(AbstractItem $item, int $quantity = 1): ItemInstance
    {
        return (new self($item))
            ->setQuantity($quantity)
            ->setWear(1.0)
        ;

    }

    public function isInstanceOf(AbstractItem $item): bool
    {
        return $this->itemId === $item->getId();
    }

    public function addQuantity(int $quantity): static
    {
        $this->quantity += $quantity;
        return $this;
    }
}
