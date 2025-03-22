<?php

namespace App\Engine\Player\Item;

use App\Engine\Item\ItemActionEngine;
use App\Engine\Player\Event\PlayerBackpackUpdateEvent;
use App\Engine\Player\Event\PlayerEquipmentUpdateEvent;
use App\Entity\Data\ItemInstance;
use App\Entity\Data\PlayerCharacter;
use App\GameElement\Item\AbstractItem;
use App\GameElement\Item\Exception\MaxBagSizeReachedException;
use App\GameElement\Item\ItemInstanceInterface;
use App\GameElement\ItemEquiment\ItemEquipmentInstanceInterface;
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
        private Environment $twig,
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

    public function takeItem(PlayerCharacter $player, AbstractItem|ItemInstanceInterface $itemInstance, int $quantity): ItemInstanceInterface
    {
        $baseBag = $player->getBackpack();
        if ($itemInstance instanceof AbstractItem) {
            $itemInstance = $baseBag->findAndExtract($itemInstance, $quantity);
        } else {
            $itemInstance = $baseBag->extract($itemInstance, $quantity);
        }
        $this->playerCharacterRepository->save($player);
        return $itemInstance;
    }

    public function equip(ItemEquipmentInstanceInterface $itemInstance, PlayerCharacter $player): void
    {
        $equipment = $this->takeItem($player, $itemInstance, 1);
        $player->getEquipment()->addItem($equipment);
        $equipment->setBag($player->getEquipment());
        $this->playerCharacterRepository->save($player);

        $this->eventDispatcher->dispatch(new PlayerBackpackUpdateEvent($player->getId()));
        $this->eventDispatcher->dispatch(new PlayerEquipmentUpdateEvent($player->getId()));
    }

    public function unequip(ItemEquipmentInstanceInterface $itemInstance, PlayerCharacter $player): void
    {
        $equipment = $player->getEquipment()->extract($itemInstance, 1);
        $player->getBackpack()->addItem($equipment);
        $equipment->setBag($player->getBackpack());
        $this->playerCharacterRepository->save($player);

        $this->eventDispatcher->dispatch(new PlayerBackpackUpdateEvent($player->getId()));
        $this->eventDispatcher->dispatch(new PlayerEquipmentUpdateEvent($player->getId()));
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

    #[AsEventListener(PlayerEquipmentUpdateEvent::class)]
    public function onPlayerEquipmentUpdateEvent(PlayerEquipmentUpdateEvent $event): void
    {
        $player = $this->playerCharacterRepository->find($event->getPlayerId());
        $this->hub->publish(new Update(
            'player_gui_' . $player->getId(),
            $this->twig->render('item_bag/space.stream.html.twig', ['bag' => $player->getEquipment()]),
            true
        ));

        $this->hub->publish(new Update(
            'player_gui_' . $player->getId(),
            $this->twig->render('item_bag/items_update.stream.html.twig', ['bag' => $player->getEquipment()]),
            true
        ));
    }
}