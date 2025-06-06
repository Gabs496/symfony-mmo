<?php

namespace App\Engine\Player;

use App\Engine\Player\Event\PlayerItemBagUpdateEvent;
use App\Entity\Data\ItemInstance;
use App\Entity\Data\PlayerCharacter;
use App\GameElement\Item\AbstractItemPrototype;
use App\GameElement\Item\Exception\MaxBagSizeReachedException;
use App\GameElement\Item\ItemInstanceInterface;
use App\GameElement\ItemEquiment\Component\ItemEquipmentComponent;
use App\GameElement\Notification\Engine\NotificationEngine;
use App\Repository\Data\PlayerCharacterRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
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

        $this->eventDispatcher->dispatch(new PlayerItemBagUpdateEvent($player->getId(), $backPack));
    }

    public function takeItem(PlayerCharacter $player, AbstractItemPrototype|ItemInstanceInterface $itemInstance, int $quantity): ItemInstanceInterface
    {
        if ($itemInstance instanceof AbstractItemPrototype) {
            $bag = $player->getBackpack();
            $itemInstance = $bag->findAndExtract($itemInstance, $quantity);
        } else {
            /** @var ItemInstance $itemInstance */
            $bag = $itemInstance->getBag();
            $itemInstance = $bag->extract($itemInstance, $quantity);
        }
        $this->playerCharacterRepository->save($player);

        $this->eventDispatcher->dispatch(new PlayerItemBagUpdateEvent($player->getId(), $bag));
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

        $this->eventDispatcher->dispatch(new PlayerItemBagUpdateEvent($player->getId(), $player->getBackpack()));
        $this->eventDispatcher->dispatch(new PlayerItemBagUpdateEvent($player->getId(), $player->getEquipment()));
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

        $this->eventDispatcher->dispatch(new PlayerItemBagUpdateEvent($player->getId(), $player->getBackpack()));
        $this->eventDispatcher->dispatch(new PlayerItemBagUpdateEvent($player->getId(),$player->getEquipment()));
    }

    #[AsEventListener(PlayerItemBagUpdateEvent::class)]
    public function onPlayerItemBagUpdated(PlayerItemBagUpdateEvent $event): void
    {
        $player = $this->playerCharacterRepository->find($event->getPlayerId());

        try {
            $this->hub->publish(new Update(
                'player_gui_' . $player->getId(),
                $this->twig->render('streams/space.stream.html.twig', ['bag' => $event->getItemBag()]),
                true
            ));

            $this->hub->publish(new Update(
                'player_gui_' . $player->getId(),
                $this->twig->render('streams/items_update.stream.html.twig', ['bag' => $event->getItemBag()]),
                true
            ));
        } catch (RuntimeError $twigError) {
            $this->logger->error($twigError->getMessage());
        }
    }
}