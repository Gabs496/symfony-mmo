<?php

namespace App\Engine\Player\Event\Handler;

use App\Engine\Player\Event\PlayerBackpackUpdateEvent;
use App\Entity\Data\PlayerCharacter;
use App\GameElement\Activity\Event\ActivityStepEndEvent;
use App\GameElement\Activity\Event\ActivityStepStartEvent;
use App\Repository\Data\PlayerCharacterRepository;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Twig\Environment;

readonly class ActivityHandler
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
            'item_bag_fullness_' . $player->getBackpack()->getId(),
            $this->twig->render('item_bag/space.stream.html.twig', ['bag' => $player->getBackpack()])
        ));
    }

    #[AsEventListener(ActivityStepStartEvent::class)]
    public function onPlayerActivityStart(ActivityStepStartEvent $event): void
    {
        $player = $event->getSubject();
        if (!$player instanceof PlayerCharacter) {
            return;
        }
        $this->hub->publish(new Update(
            'player_current_activity_' . $player->getName(),
            $this->twig->load('map/PlayerActivity.stream.html.twig')->renderBlock('start',['activity' => $player->getCurrentActivity()])
        ));
    }

    #[AsEventListener(ActivityStepEndEvent::class)]
    public function onPlayerActivityEnd(ActivityStepEndEvent $event): void
    {
        if (!$event->getSubject() instanceof PlayerCharacter) {
            return;
        }
        $player = $this->playerCharacterRepository->find($event->getSubject()->getId());
        $this->hub->publish(new Update(
            'player_current_activity_' . $player->getName(),
            $this->twig->load('map/PlayerActivity.stream.html.twig')->renderBlock('end', ['activity' => $player->getCurrentActivity()])
        ));
    }
}