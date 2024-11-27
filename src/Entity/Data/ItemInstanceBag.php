<?php

namespace App\Entity;

use App\Repository\ItemInstanceBagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ItemInstanceBagRepository::class)]
class ItemInstanceBag
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[ORM\Column(type: 'guid')]
    private ?string $id = null;

    #[ORM\ManyToOne(targetEntity: ItemInstanceBagCollection::class, inversedBy: 'bags')]
    private ItemInstanceBagCollection $collection;

    #[ORM\Column(type: 'string', length: 255)]
    private string $type;

    #[ORM\OneToMany(targetEntity: ItemInstance::class, mappedBy: 'bag')]
    private Collection $items;

    public function __construct(string $type, ItemInstanceBagCollection $collection)
    {
        $this->collection = $collection;
        $this->type = $type;
        $this->items = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getCollection(): ItemInstanceBagCollection
    {
        return $this->collection;
    }

    public function setCollection(ItemInstanceBagCollection $collection): void
    {
        $this->collection = $collection;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(ItemInstance $item): self
    {
        if (!$this->items->contains($item)) {
            $this->items->add($item);
            $item->setBag($this);
        }

        return $this;
    }
}
