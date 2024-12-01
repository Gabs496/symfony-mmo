<?php

namespace App\Entity\Data;

use App\Entity\Abstract\Character;
use App\Entity\Game\Map;
use App\Entity\Security\User;
use App\Repository\PlayerCharacterRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


#[ORM\Entity(repositoryClass: PlayerCharacterRepository::class)]
#[UniqueEntity(fields: ['name'], message: 'This name is already taken.')]
#[ORM\UniqueConstraint(columns: ['name'])]
class PlayerCharacter extends Character
{
    #[ORM\Id]
    #[ORM\Column(type: 'guid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?string $id = null;

    #[ORM\Column(length: 50)]
    protected ?string $name = null;

    /**
     * @var Collection<int, ItemInstanceBag>
     */
    #[ORM\OneToMany(targetEntity: ItemInstanceBag::class, mappedBy: 'player', cascade: ['persist', 'remove'])]
    protected Collection $itemInstanceBags;

    protected ?Map $currentPlace = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Mastery $mastery = null;

    #[ORM\ManyToOne(inversedBy: 'playerCharacters')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'playerCharacters')]
    private ?Map $position = null;

    public function __construct()
    {
        $this->itemInstanceBags = new ArrayCollection();
    }

    public function getId(): ?string
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

    public function getCurrentPlace(): ?Map
    {
        return $this->currentPlace;
    }

    public function setCurrentPlace(?Map $currentPlace): void
    {
        $this->currentPlace = $currentPlace;
    }

    public function getMastery(): ?Mastery
    {
        return $this->mastery;
    }

    public function setMastery(Mastery $mastery): static
    {
        $this->mastery = $mastery;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getPosition(): ?Map
    {
        return $this->position;
    }

    public function setPosition(?Map $position): static
    {
        $this->position = $position;

        return $this;
    }
}
