<?php

namespace App\Twig\Components;

use App\GameElement\Item\Component\ItemBagComponent;
use App\GameElement\Item\Component\ItemInBagSlotComponent;
use App\GameElement\Item\Event\ItemBagChangedEvent;
use App\GameElement\Item\ItemBagEngine;
use App\GameElement\Item\Repository\ItemInBagSlotRepository;
use PennyPHP\Core\Engine\GameObjectEngine;
use PennyPHP\Core\Entity\GameObject;
use PennyPHP\Core\GameObjectInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class ItemBag
{
    use DefaultActionTrait;

    #[LiveProp]
    public ?string $playerId = null;

    private ?ItemBagComponent $bag = null;

    public function __construct(
        private readonly ItemBagEngine           $bagEngine,
        private readonly GameObjectEngine        $gameObjectEngine,
        private readonly ItemInBagSlotRepository $itemInBagSlotRepository,
    )
    {}

    public function mount(GameObjectInterface $player): void
    {
        $this->playerId = $player->getId();
    }

    public function getBag(): ItemBagComponent
    {
        return $this->bag ?: $this->bag = $this->gameObjectEngine->get($this->playerId)->getComponent(ItemBagComponent::class);
    }

    /** Id used by ItemInBagSlotComponent::bagId and by ItemBagChangedEvent */
    public function getBagId(): string
    {
        return $this->getBag()->getGameObject()->getId();
    }

    public function getChangedEvent(): ItemBagChangedEvent
    {
        return new ItemBagChangedEvent($this->getBagId());
    }

    /** @return GameObject[] */
    public function getItems(): array
    {
        return array_map(
            fn(ItemInBagSlotComponent $slot) => $slot->getGameObject(),
            $this->itemInBagSlotRepository->findInBag($this->getBag()),
        );
    }

    public function getFullness(): float
    {
        return $this->bagEngine->getFullness($this->getBag());
    }
}
