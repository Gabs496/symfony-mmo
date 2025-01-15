<?php

namespace App\Engine\Player\Event\Handler;

use App\Engine\Player\Event\PlayerBackpackUpdated;
use App\Entity\Data\PlayerCharacter;
use App\GameElement\Activity\Event\ActivityEnded;
use App\GameElement\Activity\Event\ActivityStarted;
use App\Repository\Data\PlayerCharacterRepository;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
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

    #[AsMessageHandler]
    public function onPlayerBackpackUpdated(PlayerBackpackUpdated $event): void
    {
        $player = $this->playerCharacterRepository->find($event->getPlayerId());
        $this->hub->publish(new Update(
            'item_bag_fullness_' . $player->getBackpack()->getId(),
            $this->twig->render('item_bag/space.stream.html.twig', ['bag' => $player->getBackpack()])
        ));
    }

    #[AsMessageHandler]
    public function onPlayerActivityStart(ActivityStarted $event): void
    {
        if (!$event->getSubject() instanceof PlayerCharacter) {
            return;
        }
        $player = $this->playerCharacterRepository->find($event->getSubject()->getId());
        $this->hub->publish(new Update(
            'player_current_activity_' . $player->getName(),
            $this->twig->load('map/PlayerActivity.stream.html.twig')->renderBlock('start',['activity' => $player->getCurrentActivity()])
        ));
    }

    #[AsMessageHandler]
    public function onPlayerActivityEnd(ActivityEnded $event): void
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