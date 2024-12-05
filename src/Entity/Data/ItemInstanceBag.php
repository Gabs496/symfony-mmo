<?php

namespace App\Entity\Data;

use App\Entity\ItemBagType;
use App\Repository\Data\ItemInstanceBagRepository;
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

    #[ORM\Column(type: 'string', enumType: ItemBagType::class)]
    private ItemBagType $type;

    /** @var Collection<int, ItemInstance> */
    #[ORM\OneToMany(targetEntity: ItemInstance::class, mappedBy: 'bag', cascade: ['persist', 'remove'])]
    private Collection $items;

    public function __construct(ItemBagType $type, PlayerCharacter $player)
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

    public function getType(): ItemBagType
    {
        return $this->type;
    }

    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(ItemInstance $item): self
    {
        if ($item->isStackable()) {
            foreach ($this->items as $existingItem) {
                if ($existingItem->isInstanceOf($item->getItem())) {
                    $existingItem->addQuantity($item->getQuantity());
                    return $this;
                }
            }
        }

        $this->items->add($item);
        $item->setBag($this);

        return $this;
    }

    public function is(ItemBagType $itemBagType): bool
    {
        return $this->type === $itemBagType;
    }
}
