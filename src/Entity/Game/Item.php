<?php

namespace App\Entity;

use App\Entity\Interface\ItemInterface;
use App\Repository\ItemRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ItemRepository::class)]
#[ORM\Table(name: 'game_item')]
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
    private float $maxDurability = 0.0;

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
        private readonly float    $minExperienceRequired,
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

    public function getMinExperienceRequired(): float
    {
        return $this->minExperienceRequired;
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

    public function getMaxDurability(): float
    {
        return $this->maxDurability;
    }

    public function setMaxDurability(float $maxDurability): void
    {
        $this->maxDurability = $maxDurability;
    }

    public function getType(): ItemType
    {
        return $this->type;
    }
}
