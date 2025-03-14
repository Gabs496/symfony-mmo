<?php

namespace App\Engine\Player\Activity;

use App\Engine\Player\Event\PlayerBackpackUpdateEvent;
use App\Entity\Data\PlayerCharacter;
use App\GameElement\Activity\Event\ActivityEndEvent;
use App\GameElement\Activity\Event\ActivityStartEvent;
use App\Repository\Data\PlayerCharacterRepository;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Twig\Environment;

readonly class ActivityListener
{
    public function __construct(
        private HubInterface $hub,
        private Environment $twig,
        private PlayerCharacterRepository $playerCharacterRepository,
    )
    {

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

    #[AsEventListener(ActivityStartEvent::class)]
    public function onPlayerActivityStart(ActivityStartEvent $event): void
    {
        $player = $event->getSubject();
        if (!$player instanceof PlayerCharacter) {
            return;
        }

        $player->startActivity($event->getActivity()->getEntity());
        $this->playerCharacterRepository->save($player);

        $this->hub->publish(new Update(
            'player_gui_' . $player->getId(),
            $this->twig->load('map/PlayerActivity.stream.html.twig')->renderBlock('start',['activity' => $player->getCurrentActivity()]),
            true
        ));
    }

    #[AsEventListener(ActivityEndEvent::class)]
    public function onPlayerActivityEnd(ActivityEndEvent $event): void
    {
        $player = $event->getSubject();
        if (!$player instanceof PlayerCharacter) {
            return;
        }
        $player->endCurrentActivity();

        $this->hub->publish(new Update(
            'player_gui_' . $player->getId(),
            $this->twig->load('map/PlayerActivity.stream.html.twig')->renderBlock('end', ['activity' => $event->getActivity()->getEntity()]),
            true
        ));
    }
}