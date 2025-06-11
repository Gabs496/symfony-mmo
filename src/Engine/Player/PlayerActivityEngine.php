<?php

namespace App\Engine\Player;

use App\Entity\Data\PlayerCharacter;
use App\GameElement\Activity\Event\ActivityEndEvent;
use App\GameElement\Activity\Event\ActivityStartEvent;
use App\GameElement\Activity\Event\BeforeActivityStartEvent;
use App\GameElement\Activity\Exception\ActivityUnexpectedStopException;
use App\GameElement\Notification\Exception\UserNotificationException;
use App\Repository\Data\ActivityRepository;
use App\Repository\Data\PlayerCharacterRepository;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Twig\Environment;

#[AsEventListener(event: 'kernel.exception', method: 'onActivityUnexpectedStopException')]
readonly class PlayerActivityEngine implements EventSubscriberInterface
{
    public function __construct(
        private HubInterface $hub,
        private Environment $twig,
        private PlayerCharacterRepository $playerCharacterRepository,
        private ActivityRepository $activityRepository,
    )
    {

    }

    public static function getSubscribedEvents(): array
    {
        return [
            BeforeActivityStartEvent::class => [
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
        $player = $event->getActivity()->getSubject();
        if (!$player instanceof PlayerCharacter) {
            return;
        }
        if ($player->getCurrentActivity()) {
            throw new UserNotificationException($player->getId(), 'You are too busy',);
        }
    }

    public function lockPlayer(ActivityStartEvent $event): void
    {
        $player = $event->getActivity()->getSubject();
        if (!$player instanceof PlayerCharacter) {
            return;
        }

        $activityEntity = $this->activityRepository->find($event->getActivity()->getEntityId());
        $player->startActivity($activityEntity);
        $this->playerCharacterRepository->save($player);

        $this->hub->publish(new Update(
            'player_gui_' . $player->getId(),
            $this->twig->load('streams/PlayerActivity.stream.html.twig')->renderBlock('start',['activity' => $player->getCurrentActivity()]),
            true
        ));
    }

    public function onActivityEnd(ActivityEndEvent $event): void
    {
        $player = $event->getActivity()->getSubject();
        if (!$player instanceof PlayerCharacter) {
            return;
        }

        $this->unlockPlayer($player);
    }

    public function onActivityUnexpectedStopException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        if (!$exception instanceof ActivityUnexpectedStopException) {
            return;
        }

        $activity = $exception->getActivity();
        $subject = $activity->getSubject();
        if (!$subject instanceof PlayerCharacter) {
            return;
        }

        $this->unlockPlayer($subject);
    }

    public function unlockPlayer(PlayerCharacter $player): void
    {
        $activityEntity = $player->getCurrentActivity();
        $player->endCurrentActivity();
        $this->playerCharacterRepository->save($player);

        $activityEntity = $this->activityRepository->find($activityEntity->getId());

        $this->hub->publish(new Update(
            'player_gui_' . $player->getId(),
            $this->twig->load('streams/PlayerActivity.stream.html.twig')->renderBlock('end', ['activity' => $activityEntity]),
            true
        ));
    }
}