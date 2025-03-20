<?php

namespace App\Entity\Data;

use App\GameElement\Item\AbstractItem;
use App\GameElement\Item\AbstractItemBag;
use App\GameElement\Item\Exception\ItemQuantityNotAvailableException;
use App\GameElement\Item\ItemInstanceInterface;
use App\Repository\Data\ItemInstanceBagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\MappedSuperclass]
#[ORM\InheritanceType(value: 'SINGLE_TABLE')]
#[ORM\DiscriminatorColumn(name: 'type', type: 'string')]
#[ORM\Entity(repositoryClass: ItemInstanceBagRepository::class)]
abstract class ItemBag extends AbstractItemBag
{
    #[ORM\Id]
    #[ORM\Column(type: 'guid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?string $id = null;

    #[ORM\OneToOne(targetEntity: PlayerCharacter::class, mappedBy: 'backpack')]
    private PlayerCharacter $player;

    /** @var Collection<int, ItemInstance> */
    #[ORM\OneToMany(targetEntity: ItemInstance::class, mappedBy: 'bag', cascade: ['persist', 'remove'], orphanRemoval: true)]
    protected iterable $items;

    #[ORM\Column(type: 'float')]
    protected float $size;

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

    /**
     * @throws ItemQuantityNotAvailableException
     */
    public function extract(AbstractItem $item, int $quantity): ItemInstanceInterface
    {
        foreach ($this->items as $key => $itemInstance) {
            if ($itemInstance->isInstanceOf($item) && $itemInstance->getQuantity() >= $quantity) {
                $itemInstance->setQuantity($itemInstance->getQuantity() - $quantity);
                $extracted = clone $itemInstance;
                $extracted->setQuantity($quantity);
                if ($itemInstance->getQuantity() <= 0) {
                    $this->items->remove($key);
                }
                return $extracted;
            }
        }

        throw new ItemQuantityNotAvailableException(sprintf('%s quantity (%s) not available', $item->getName(), $quantity));
    }
}
