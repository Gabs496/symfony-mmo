<?php

namespace App\Engine\Player;

use App\Engine\Player\Event\PlayerItemBagUpdateEvent;
use App\Entity\Data\PlayerCharacter;
use App\Entity\Game\GameObject;
use App\GameElement\Core\GameObject\GameObjectPrototypeInterface;
use App\GameElement\Item\Exception\MaxBagSizeReachedException;
use App\GameElement\ItemEquiment\Component\ItemEquipmentComponent;
use App\GameElement\Notification\Engine\NotificationEngine;
use App\Repository\Data\ItemBagRepository;
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
        private EventDispatcherInterface  $eventDispatcher,
        private PlayerCharacterRepository $playerCharacterRepository,
        private HubInterface              $hub,
        private Environment               $twig,
        private NotificationEngine        $notificationEngine,
        private LoggerInterface           $logger,
        private ItemBagRepository         $itemBagRepository,
    )
    {
    }
    /** @throws MaxBagSizeReachedException */
    public function giveItem(PlayerCharacter $player, GameObject $item): void
    {
        $backPack = $player->getBackpack();
        $backPack->addItem($item);
        $this->itemBagRepository->save($backPack);

        $this->eventDispatcher->dispatch(new PlayerItemBagUpdateEvent($player->getId(), $backPack));
    }

    public function takeItem(PlayerCharacter $player, GameObjectPrototypeInterface|GameObject $item, int $quantity): GameObject
    {
        $bag = $player->getBackpack();
        if ($item instanceof GameObjectPrototypeInterface) {
            $item = $bag->findAndExtract($item, $quantity);
            /** @var GameObject $item */
        } else {
            $item = $bag->extract($item, $quantity);
        }
        $this->playerCharacterRepository->save($player);

        $this->eventDispatcher->dispatch(new PlayerItemBagUpdateEvent($player->getId(), $bag));
        return $item;
    }

    public function equip(GameObject $item, PlayerCharacter $player): void
    {
        if (!$item->hasComponent(ItemEquipmentComponent::class)) {
            return;
        }
        $equipment = $this->takeItem($player, $item, 1);

        try {
            $player->getEquipment()->addItem($equipment);
            $this->itemBagRepository->save($player->getEquipment());
        } catch (MaxBagSizeReachedException) {
            $this->giveItem($player, $equipment);
            $this->notificationEngine->danger($player->getId(), 'Your equipment is full, you cannot equip the item.');
        }

        $this->eventDispatcher->dispatch(new PlayerItemBagUpdateEvent($player->getId(), $player->getBackpack()));
        $this->eventDispatcher->dispatch(new PlayerItemBagUpdateEvent($player->getId(), $player->getEquipment()));
    }

    public function unequip(GameObject $item, PlayerCharacter $player): void
    {
        if (!$item->hasComponent(ItemEquipmentComponent::class)) {
            return;
        }

        $equipment = $player->getEquipment()->extract($item, 1);
        $this->itemBagRepository->save($player->getEquipment());
        $player->getBackpack()->addItem($equipment);
        $this->itemBagRepository->save($player->getBackpack());

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