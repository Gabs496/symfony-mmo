<?php

namespace App\Engine\Player;

use App\Engine\Player\Event\PlayerBackpackUpdated;
use App\Entity\Data\PlayerCharacter;
use App\GameElement\Activity\Event\ActivityStarted;
use App\Repository\Data\PlayerCharacterRepository;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Twig\Environment;

readonly class PlayerEventHandler
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
            $this->twig->render('map/PlayerActivity.stream.html.twig', ['activity' => $player->getCurrentActivity()])
        ));
    }
}