<?php

namespace App\Entity\Data;

use App\Entity\Game\GameObject;
use App\GameElement\Core\GameObject\GameObjectInterface;
use App\GameElement\Item\AbstractItemBag;
use App\Repository\Data\ItemObjectRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: ItemObjectRepository::class)]
class ItemObject
{
    #[ORM\Id]
    #[ORM\Column(type: 'guid')]
    private string $id;

    public function __construct(
        #[ORM\OneToOne(cascade: ['persist', 'remove'])]
        #[ORM\JoinColumn(nullable: false)]
        private GameObject $gameObject,
        #[ORM\ManyToOne(inversedBy: 'itemObjects')]
        #[ORM\JoinColumn(nullable: false)]
        private ItemBag $bag
    )
    {
        $this->id = Uuid::v7();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getBag(): ?AbstractItemBag
    {
        return $this->bag;
    }

    public function setBag(?ItemBag $bag): static
    {
        $this->bag = $bag;

        return $this;
    }

    /** @return GameObject|GameObjectInterface */
    public function getGameObject(): ?GameObjectInterface
    {
        return $this->gameObject;
    }

    public function setGameObject(GameObject $gameObject): static
    {
        $this->gameObject = $gameObject;

        return $this;
    }
}
