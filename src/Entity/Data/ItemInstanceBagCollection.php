<?php

namespace App\Entity\Data;

use App\Repository\ItemInstanceBagCollectionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ItemInstanceBagCollectionRepository::class)]
class ItemInstanceBagCollection
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[ORM\Column(type: 'guid')]
    private ?string $id = null;

    private Collection $bags;

    public function __construct()
    {
        $this->bags = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getBags(): Collection
    {
        return $this->bags;
    }

    public function addBag(ItemInstanceBag $bag): self
    {
        if (!$this->bags->contains($bag)) {
            $this->bags->add($bag);
            $bag->setCollection($this);
        }

        return $this;
    }
}
