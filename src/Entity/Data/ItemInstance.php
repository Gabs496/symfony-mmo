<?php

namespace App\Entity\Data;

use App\GameElement\Core\GameObject\GameObjectReference;
use App\GameElement\Item\AbstractItemBag;
use App\GameElement\Item\AbstractItemInstance;
use App\GameElement\Item\AbstractItemPrototype;
use App\GameObject\Item\AbstractBaseItemPrototype;
use App\Repository\Data\ItemInstanceRepository;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: ItemInstanceRepository::class)]
class ItemInstance extends AbstractItemInstance
{
    #[ORM\Id]
    #[ORM\Column(type: 'guid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?string $id = null;

    #[ORM\ManyToOne(targetEntity: ItemBag::class, inversedBy: 'items')]
    protected ?AbstractItemBag $bag = null;

    #[ORM\Column(type: 'integer')]
    protected int $quantity = 1;

    #[ORM\Column(type: 'json_document', nullable: false)]
    protected array $components = [];

    #[ORM\Column(length: 50)]
    protected string $itemPrototypeId;

    /** @var AbstractBaseItemPrototype|null  */
    #[GameObjectReference(AbstractBaseItemPrototype::class, objectIdProperty: 'itemPrototypeId')]
    protected ?AbstractItemPrototype $itemPrototype = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getBag(): ?AbstractItemBag
    {
        return $this->bag;
    }

    public function setBag(?AbstractItemBag $bag): ItemInstance
    {
        $this->bag = $bag;

        return $this;
    }

    public function addQuantity(int $quantity): static
    {
        $this->quantity += $quantity;
        return $this;
    }

    public function getItemPrototypeId(): string
    {
        return $this->itemPrototypeId;
    }

    public function setItemPrototypeId(string $itemPrototypeId): ItemInstance
    {
        $this->itemPrototypeId = $itemPrototypeId;

        return $this;
    }

    /** @deprecated Use {@link ItemInstance::getItemPrototype()} */
    public function getItem(): AbstractItemPrototype
    {
        return $this->getItemPrototype();
    }

    public function setComponents(array $components): ItemInstance
    {
        $this->components = $components;

        return $this;
    }
}
