<?php

namespace App\Entity\Data;

use App\Repository\ItemInstanceBagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ItemInstanceBagRepository::class)]
class ItemInstanceBag
{
    #[ORM\Id]
    #[ORM\Column(type: 'guid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?string $id = null;

    #[ORM\ManyToOne(targetEntity: PlayerCharacter::class, inversedBy: 'itemInstanceBags')]
    private PlayerCharacter $player;

    #[ORM\Column(type: 'string', length: 255)]
    private string $type;

    #[ORM\OneToMany(targetEntity: ItemInstance::class, mappedBy: 'bag')]
    private Collection $items;

    public function __construct(string $type, PlayerCharacter $player)
    {
        $this->player = $player;
        $this->type = $type;
        $this->items = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getPlayer(): PlayerCharacter
    {
        return $this->player;
    }

    public function setPlayer(PlayerCharacter $player): void
    {
        $this->player = $player;
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
