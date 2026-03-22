<?php

namespace App\Engine\Player;

use App\Entity\Data\Player;
use App\GameElement\Activity\Event\ActivityEndEvent;
use App\GameElement\Activity\Event\ActivityStartEvent;
use App\GameElement\Activity\Event\BeforeActivityStartEvent;
use App\GameElement\Notification\Exception\UserNotificationException;
use App\Repository\Data\ActivityRepository;
use App\Repository\Data\PlayerCharacterRepository;
use App\Stream\PlayerActivityStream;
use App\Stream\Streamer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

readonly class PlayerActivityEngine implements EventSubscriberInterface
{
    public function __construct(
        private PlayerCharacterRepository $playerCharacterRepository,
        private ActivityRepository        $activityRepository,
        private Streamer $streamer,
    )
    {

    }

    public static function getSubscribedEvents(): array
    {
        return [
            BeforeActivityStartEvent::class => [
                ['unlockIfShouldBeUnlocked', 0],
                ['checkIfPlayerLocked', 0],
            ],
            ActivityStartEvent::class => [
                ['lockPlayer', 0],
            ],
            ActivityEndEvent::class => [
                ['onActivityEnd', 0],
            ],
        ];
    }

    public function checkIfPlayerLocked(BeforeActivityStartEvent $event): void
    {
        $player = $this->playerCharacterRepository->findOneBy(['gameObject' => $event->getActivity()->getSubject()]);
        if (!$player instanceof Player) {
            return;
        }
        if ($player->getCurrentActivity()) {
            throw new UserNotificationException($player->getId(), 'You are too busy',);
        }
    }

    public function lockPlayer(ActivityStartEvent $event): void
    {
        $player = $this->playerCharacterRepository->findOneBy(['gameObject' => $event->getActivity()->getSubject()]);
        if (!$player instanceof Player) {
            return;
        }

        $activityEntity = $this->activityRepository->find($event->getActivity()->getEntityId());
        $player->startActivity($activityEntity);
        $this->playerCharacterRepository->save($player);

        $this->streamer->send(new PlayerActivityStream($activityEntity, 'start', $player));
    }

    public function onActivityEnd(ActivityEndEvent $event): void
    {
        $player = $this->playerCharacterRepository->findOneBy(['gameObject' => $event->getActivity()->getSubject()]);
        if (!$player instanceof Player) {
            return;
        }

        $this->unlockPlayer($player);
    }

    public function unlockPlayer(Player $player): void
    {
        $activityEntity = $player->getCurrentActivity();
        if (!$activityEntity) {
            return;
        }

        $player->endCurrentActivity();
        $this->playerCharacterRepository->save($player);

        $activityEntity = $this->activityRepository->find($activityEntity->getId());

        $this->streamer->send(new PlayerActivityStream($activityEntity, 'end', $player));
    }

    public function unlockIfShouldBeUnlocked(BeforeActivityStartEvent $event): void
    {
        $player = $this->playerCharacterRepository->findOneBy(['gameObject' => $event->getActivity()->getSubject()]);
        if (!$player instanceof Player) {
            return;
        }
        if (!$currentActivity = $player->getCurrentActivity()) {
            return;
        }

        if ($currentActivity->getCompletedAt() || $currentActivity->shouldBeFinished()) {
            $this->unlockPlayer($player);
        }
    }
}