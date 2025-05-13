<?php

namespace App\Engine\Player;

use App\Engine\PlayerCharacter;
use App\GameElement\Activity\Event\ActivityEndEvent;
use App\GameElement\Activity\Event\ActivityStartEvent;
use App\Repository\Data\ActivityRepository;
use App\Repository\Data\PlayerCharacterRepository;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Twig\Environment;

readonly class PlayerActivityEngine
{
    public function __construct(
        private HubInterface $hub,
        private Environment $twig,
        private PlayerCharacterRepository $playerCharacterRepository,
        private ActivityRepository $activityRepository,
    )
    {

    }

    #[AsEventListener(ActivityStartEvent::class)]
    public function onPlayerActivityStart(ActivityStartEvent $event): void
    {
        $subject = $event->getSubject();
        if (!$subject instanceof PlayerCharacter) {
            return;
        }
        $player = $this->playerCharacterRepository->find($subject->getId());
        if (!$player instanceof \App\Entity\Data\PlayerCharacter) {
            return;
        }

        $activityEntity = $this->activityRepository->find($event->getActivity()->getEntityId());
        $player->startActivity($activityEntity);
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
        $subject = $event->getSubject();
        if (!$subject instanceof PlayerCharacter) {
            return;
        }
        $player = $this->playerCharacterRepository->find($subject->getId());
        if (!$player instanceof \App\Entity\Data\PlayerCharacter) {
            return;
        }
        $player->endCurrentActivity();

        $activityEntity = $this->activityRepository->find($event->getActivity()->getEntityId());

        $this->hub->publish(new Update(
            'player_gui_' . $player->getId(),
            $this->twig->load('map/PlayerActivity.stream.html.twig')->renderBlock('end', ['activity' => $activityEntity]),
            true
        ));
    }
}