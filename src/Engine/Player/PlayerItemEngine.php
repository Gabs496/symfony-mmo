<?php

namespace App\Engine\Player;

use App\Engine\Player\Event\PlayerItemBagUpdateEvent;
use App\Entity\Core\GameObject;
use App\Entity\Data\PlayerCharacter;
use App\GameElement\Core\GameObject\GameObjectInterface;
use App\GameElement\Core\GameObjectPrototype\GameObjectPrototypeInterface;
use App\GameElement\Item\Exception\MaxBagSizeReachedException;
use App\GameElement\Item\ItemEngineInterface;
use App\GameElement\ItemEquiment\Component\ItemEquipmentComponent;
use App\GameElement\Notification\Exception\UserNotificationException;
use App\Repository\Data\ItemBagRepository;
use App\Repository\Data\PlayerCharacterRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Twig\Environment;
use Twig\Error\RuntimeError;

readonly class PlayerItemEngine implements ItemEngineInterface
{
    public function __construct(
        private EventDispatcherInterface  $eventDispatcher,
        private PlayerCharacterRepository $playerCharacterRepository,
        private HubInterface              $hub,
        private Environment               $twig,
        private LoggerInterface           $logger,
        private ItemBagRepository         $itemBagRepository,
    )
    {
    }
    /** @throws MaxBagSizeReachedException */
    public function give(GameObjectInterface $to, GameObjectInterface $item): void
    {
        $player = $this->playerCharacterRepository->findOneBy(['gameObject' => $to]);
        $backPack = $player->getBackpack();
        $backPack->addItem($item);
        $this->itemBagRepository->save($backPack);

        $this->eventDispatcher->dispatch(new PlayerItemBagUpdateEvent($player->getId(), $backPack));
    }

    public function take(GameObjectInterface $from, GameObjectInterface $item, int $quantity): GameObject
    {
        $player = $this->playerCharacterRepository->findOneBy(['gameObject' => $from]);
        $bag = $player->getBackpack();
        if ($item instanceof GameObjectPrototypeInterface) {
            $item = $bag->findAndExtract($item, $quantity);
            /** @var GameObject $item */
        } else {
            $item = $bag->extract($item, $quantity);
        }
        $this->itemBagRepository->save($bag);

        $this->eventDispatcher->dispatch(new PlayerItemBagUpdateEvent($player->getId(), $bag));
        return $item;
    }

    public function equip(GameObject $item, PlayerCharacter $player): void
    {
        if (!$item->hasComponent(ItemEquipmentComponent::class)) {
            return;
        }
        $equipment = $this->take($player->getGameObject(), $item, 1);

        try {
            $player->getEquipment()->addItem($equipment);
            $this->itemBagRepository->save($player->getEquipment());
        } catch (MaxBagSizeReachedException) {
            $this->give($player->getGameObject(), $equipment);
            throw new UserNotificationException($player->getId(), 'Your equipment is full, you cannot equip the item.');
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
        try {
            $this->hub->publish(new Update(
                'player_gui_' . $event->getPlayerId(),
                $this->twig->render('streams/space.stream.html.twig', ['bag' => $event->getItemBag()]),
                true
            ));

            $this->hub->publish(new Update(
                'player_gui_' . $event->getPlayerId(),
                $this->twig->render('item_bag/content.stream.html.twig', ['bag' => $event->getItemBag()]),
                true
            ));
        } catch (RuntimeError $twigError) {
            $this->logger->error($twigError->getMessage());
        }
    }
}