<?php

namespace App\Entity\Item;

use App\Entity\Data\PlayerCharacter;
use App\GameElement\Item\AbstractItemBag;
use App\Repository\Data\ItemBagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\MappedSuperclass]
#[ORM\InheritanceType(value: 'SINGLE_TABLE')]
#[ORM\DiscriminatorColumn(name: 'type', type: 'string')]
#[ORM\Entity(repositoryClass: ItemBagRepository::class)]
abstract class ItemBag extends AbstractItemBag
{
    #[ORM\Id]
    #[ORM\Column(type: 'guid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?string $id = null;

    #[ORM\OneToOne(targetEntity: PlayerCharacter::class, mappedBy: 'backpack')]
    private PlayerCharacter $player;

    #[ORM\Column(type: 'float')]
    protected float $size;

    /**
     * @var Collection<int, ItemObject>
     */
    #[ORM\OneToMany(targetEntity: ItemObject::class, mappedBy: 'bag', cascade: ['persist', 'remove'], orphanRemoval: true)]
    protected iterable $items = [];

    public function __construct(PlayerCharacter $player, float $size)
    {
        parent::__construct($size);
        $this->player = $player;
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
}
