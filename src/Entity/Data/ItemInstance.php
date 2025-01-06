<?php

namespace App\Entity\Data;

use App\Core\GameObject\GameObjectReference;
use App\GameElement\Item\AbstractItem;
use App\GameElement\Item\AbstractItemBag;
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
    protected ?AbstractItemBag $bag = null;

    #[ORM\Column(type: 'integer')]
    protected int $quantity = 1;

    #[ORM\Column(type: 'float')]
    protected float $wear;

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

    public function addQuantity(int $quantity): static
    {
        $this->quantity += $quantity;
        return $this;
    }
}
