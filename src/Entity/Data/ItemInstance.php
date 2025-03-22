<?php

namespace App\Entity\Data;

use App\GameElement\Core\GameObject\GameObjectReference;
use App\GameElement\Item\AbstractItem;
use App\GameElement\Item\AbstractItemBag;
use App\GameElement\Item\ItemInstanceTrait;
use App\GameElement\Item\AbstractItemInstanceProperty;
use App\GameElement\Item\Exception\ItemInstancePropertyNotSetException;
use App\GameElement\Item\ItemInstanceInterface;
use App\Repository\Data\ItemInstanceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\InheritanceType(value: 'SINGLE_TABLE')]
#[ORM\DiscriminatorColumn(name: 'type', type: 'string')]
#[ORM\Entity(repositoryClass: ItemInstanceRepository::class)]
class ItemInstance implements ItemInstanceInterface
{
    use ItemInstanceTrait;

    #[ORM\Id]
    #[ORM\Column(type: 'guid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?string $id = null;

    #[ORM\Column(length: 50)]
    protected string $itemId;

    #[ORM\ManyToOne(targetEntity: ItemBag::class, inversedBy: 'items')]
    protected ?AbstractItemBag $bag = null;

    #[ORM\Column(type: 'integer')]
    protected int $quantity = 1;

    #[ORM\Column(type: 'float')]
    protected float $wear = 0.0;

    #[GameObjectReference(AbstractItem::class, objectIdProperty: 'itemId')]
    protected AbstractItem $item;

    #[ORM\Column(type: 'json_document', nullable: false)]
    protected array $properties = [];

    public function __construct(AbstractItem $item)
    {
        $this->item = $item;
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

    public function getBag(): ?AbstractItemBag
    {
        return $this->bag;
    }

    public function setBag(?AbstractItemBag $bag): void
    {
        $this->bag = $bag;
    }

    public function getWear(): float
    {
        return $this->wear;
    }

    public function setWear(float $wear): void
    {
        $this->wear = $wear;
    }

    public function addQuantity(int $quantity): static
    {
        $this->quantity += $quantity;
        return $this;
    }

    public function get(string $propertyName): AbstractItemInstanceProperty
    {
        if (!isset($this->properties[$propertyName])) {
            throw new ItemInstancePropertyNotSetException(sprintf('Property %s not found in item instance %s', $propertyName, $this::class));
        }

        return $this->properties[$propertyName];
    }

    public function set(AbstractItemInstanceProperty $property): self
    {
        $this->properties[$property::class] = $property;
        return $this;
    }

    public function getProperties(): array
    {
        return $this->properties;
    }
}
