<?php

namespace App\Engine\Player;

use App\Engine\Player\Event\PlayerBackpackUpdateEvent;
use App\Engine\Player\Event\PlayerEquipmentUpdateEvent;
use App\Entity\Data\ItemInstance;
use App\Entity\Data\PlayerCharacter;
use App\GameElement\Item\AbstractItemPrototype;
use App\GameElement\Item\Exception\MaxBagSizeReachedException;
use App\GameElement\Item\ItemInstanceInterface;
use App\GameElement\ItemEquiment\Component\ItemEquipmentComponent;
use App\GameElement\Notification\Engine\NotificationEngine;
use App\Repository\Data\PlayerCharacterRepository;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Twig\Environment;
use Twig\Error\RuntimeError;

readonly class PlayerItemEngine
{
    public function __construct(
        private EventDispatcherInterface $eventDispatcher,
        private PlayerCharacterRepository $playerCharacterRepository,
        private HubInterface $hub,
        private Environment $twig,
        private NotificationEngine $notificationEngine,
        private LoggerInterface $logger,
    )
    {
    }
    /** @throws MaxBagSizeReachedException */
    public function  giveItem(PlayerCharacter $player, ItemInstance $itemInstance): void
    {
        $backPack = $player->getBackpack();
        $itemInstance->setBag($backPack);
        $backPack->addItem($itemInstance);

        $this->playerCharacterRepository->save($player);

        $this->eventDispatcher->dispatch(new PlayerBackpackUpdateEvent($player->getId()));
    }

    public function takeItem(PlayerCharacter $player, AbstractItemPrototype|ItemInstanceInterface $itemInstance, int $quantity): ItemInstanceInterface
    {
        $baseBag = $player->getBackpack();
        if ($itemInstance instanceof AbstractItemPrototype) {
            $itemInstance = $baseBag->findAndExtract($itemInstance, $quantity);
        } else {
            $itemInstance = $baseBag->extract($itemInstance, $quantity);
        }
        $this->playerCharacterRepository->save($player);

        $this->eventDispatcher->dispatch(new PlayerBackpackUpdateEvent($player->getId()));
        return $itemInstance;
    }

    public function equip(ItemInstance $itemInstance, PlayerCharacter $player): void
    {
        if (!$itemInstance->hasComponent(ItemEquipmentComponent::class)) {
            return;
        }
        $equipment = $this->takeItem($player, $itemInstance, 1);

        try {
            $player->getEquipment()->addItem($equipment);
            $equipment->setBag($player->getEquipment());
            $this->playerCharacterRepository->save($player);
        } catch (MaxBagSizeReachedException $exception) {
            $this->giveItem($player, $equipment);
            $this->notificationEngine->danger($player->getId(), 'Your equipment is full, you cannot equip the item.');
        }

        $this->eventDispatcher->dispatch(new PlayerBackpackUpdateEvent($player->getId()));
        $this->eventDispatcher->dispatch(new PlayerEquipmentUpdateEvent($player->getId()));
    }

    public function unequip(ItemInstance $itemInstance, PlayerCharacter $player): void
    {
        if (!$itemInstance->hasComponent(ItemEquipmentComponent::class)) {
            return;
        }

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

        try {
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
        } catch (RuntimeError $twigError) {
            $this->logger->error($twigError->getMessage());
        }
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