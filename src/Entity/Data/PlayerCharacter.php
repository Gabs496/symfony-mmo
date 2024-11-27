<?php

namespace App\Entity\Data;

use App\Entity\Abstract\Character;
use App\Entity\Game\Map;
use App\Repository\PlayerCharacterRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlayerCharacterRepository::class)]
class PlayerCharacter extends Character
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[ORM\Column(type: 'guid')]
    private ?string $id = null;

    #[ORM\Column(length: 50)]
    protected ?string $name = null;

    #[ORM\OneToOne(targetEntity: ItemInstanceBagCollection::class, cascade: ['persist', 'remove'])]
    protected ?ItemInstanceBagCollection $itemInstanceBagCollection = null;

    protected ?Map $currentPlace = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getItemInstanceBagCollection(): ?ItemInstanceBagCollection
    {
        return $this->itemInstanceBagCollection;
    }

    public function setItemInstanceBagCollection(?ItemInstanceBagCollection $itemInstanceBagCollection): void
    {
        $this->itemInstanceBagCollection = $itemInstanceBagCollection;
    }

    public function getCurrentPlace(): ?Map
    {
        return $this->currentPlace;
    }

    public function setCurrentPlace(?Map $currentPlace): void
    {
        $this->currentPlace = $currentPlace;
    }
}
