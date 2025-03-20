<?php

namespace App\Engine\Player\Item;

use App\Engine\Player\Event\PlayerBackpackUpdateEvent;
use App\Entity\Data\ItemInstance;
use App\Entity\Data\PlayerCharacter;
use App\GameElement\Item\AbstractItem;
use App\GameElement\Item\Exception\MaxBagSizeReachedException;
use App\GameElement\Item\ItemInstanceInterface;
use App\GameElement\ItemEquiment\AbstractItemEquipment;
use App\GameObject\Item\Equipment\AbstractBaseEquipment;
use App\Repository\Data\PlayerCharacterRepository;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Twig\Environment;

readonly class PlayerItemEngine
{
    public function __construct(
        private EventDispatcherInterface $eventDispatcher,
        private PlayerCharacterRepository $playerCharacterRepository,
        private HubInterface $hub,
        private Environment $twig
    )
    {
    }
    /** @throws MaxBagSizeReachedException */
    public function giveItem(PlayerCharacter $player, ItemInstance $itemInstance): void
    {
        $backPack = $player->getBackpack();
        $itemInstance->setBag($backPack);
        $backPack->addItem($itemInstance);

        $this->eventDispatcher->dispatch(new PlayerBackpackUpdateEvent($player->getId()));
    }

    public function takeItem(PlayerCharacter $player, AbstractItem $item, int $quantity): ItemInstanceInterface
    {
        $item = $player->getBackpack()->extract($item, $quantity);
        $this->eventDispatcher->dispatch(new PlayerBackpackUpdateEvent($player->getId()));
        return $item;
    }

    //TODO: change the logic. Need to pass ItemInstanceInterface
    public function equip(AbstractItemEquipment $equipment, PlayerCharacter $player): void
    {
        if ($player->getEquipment()->isFull()) {
            return;
        }
        $equipment = $this->takeItem($player, $equipment, 1);
        $player->getEquipment()->addItem($equipment);
    }

    #[AsEventListener(PlayerBackpackUpdateEvent::class)]
    public function onPlayerBackpackUpdated(PlayerBackpackUpdateEvent $event): void
    {
        $player = $this->playerCharacterRepository->find($event->getPlayerId());
        $this->hub->publish(new Update(
            'player_gui_' . $player->getId(),
            $this->twig->render('item_bag/space.stream.html.twig', ['bag' => $player->getBackpack()]),
            true
        ));
        $this->hub->publish(new Update(
            'player_gui_' . $player->getId(),
            $this->twig->render('item_bag/items_update.stream.html.twig', ['bag' => $player->getBackpack()]),
            true
        ));
    }
}