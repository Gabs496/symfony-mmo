<?php

namespace App\Entity\Game;

use App\Entity\Interface\ItemInterface;
use App\Repository\ItemRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ItemRepository::class)]
class Item implements ItemInterface
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 50)]
    private ?string $id = null;

    #[ORM\Column(type: 'boolean')]
    private bool $equippable = false;

    #[ORM\Column(type: 'boolean')]
    private bool $consumable = false;

    #[ORM\Column(type: 'boolean')]
    private bool $stackable = false;

    #[ORM\Column(type: 'float')]
    private float $maxCondition = 0.0;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $icon;

    public function __construct(
        #[ORM\ManyToOne(targetEntity: ItemType::class)]
        private readonly ItemType $type,
        #[ORM\Column(type: 'string', length: 100)]
        private readonly string   $name,
        #[ORM\Column(type: 'string', length: 255)]
        private readonly string   $description,
        #[ORM\Column(type: 'float')]
        private readonly float    $weight,
        #[ORM\Column(type: 'float')]
        private readonly float    $advisedExperience,
    )
    {
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getWeight(): float
    {
        return $this->weight;
    }

    public function getAdvisedExperience(): float
    {
        return $this->advisedExperience;
    }

    public function isEquippable(): bool
    {
        return $this->equippable;
    }

    public function setEquippable(bool $equippable): void
    {
        $this->equippable = $equippable;
    }

    public function isConsumable(): bool
    {
        return $this->consumable;
    }

    public function setConsumable(bool $consumable): void
    {
        $this->consumable = $consumable;
    }

    public function isStackable(): bool
    {
        return $this->stackable;
    }

    public function setStackable(bool $stackable): void
    {
        $this->stackable = $stackable;
    }

    public function getMaxCondition(): float
    {
        return $this->maxCondition;
    }

    public function setMaxCondition(float $maxCondition): void
    {
        $this->maxCondition = $maxCondition;
    }

    public function getType(): ItemType
    {
        return $this->type;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setIcon(?string $icon): void
    {
        $this->icon = $icon;
    }
}
