<?php

namespace App\Engine\MapAvailableActivity\Event\Handler;

use App\Entity\Data\PlayerCharacter;
use App\GameElement\Activity\Engine\ActivityEngine;
use App\GameElement\Activity\Event\ActivityEndEvent;
use App\GameElement\Activity\Event\ActivityStartEvent;
use App\GameElement\Gathering\Activity\ResourceGatheringActivity;
use App\Repository\Data\MapAvailableActivityRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Twig\Environment;

readonly class ActivityHandler implements EventSubscriberInterface
{
    public function __construct(
        private HubInterface       $hub,
        private Environment        $twig,
        private MapAvailableActivityRepository $mapAvailableActivityRepository,
        private ActivityEngine     $activityEngine,
    )
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ActivityStartEvent::class => [
                ['lockMapAvailableActivity'],
                ['streamActvityStart'],
            ],
            ActivityEndEvent::class => [
                ['consumeMapAvailableActivity'],
                ['continueUntillEmpty', -1],
            ],
        ];
    }

    public function lockMapAvailableActivity(ActivityStartEvent $event): void
    {
        $activity = $event->getActivity();
        if (!$activity instanceof ResourceGatheringActivity) {
            return;
        }

        $subject = $event->getSubject();
        if (!$subject instanceof PlayerCharacter) {
            return;
        }

        $mapAvailableActivity = $activity->getMapAvailableActivity();
        $mapAvailableActivity->startActivity($activity->getEntity());
        $this->mapAvailableActivityRepository->save($mapAvailableActivity);
    }

    public function streamActvityStart(ActivityStartEvent $event): void
    {
        $activity = $event->getActivity();
        if (!$activity instanceof ResourceGatheringActivity) {
            return;
        }

        $subject = $event->getSubject();
        if (!$subject instanceof PlayerCharacter) {
            return;
        }

        $mapAvailableActivity = $activity->getMapAvailableActivity();
        $this->hub->publish(new Update(['mapAvailableActivities_' . $mapAvailableActivity->getMapId()],
            $this->twig->load('map/MapAvailableActivity.stream.html.twig')->renderBlock('update', ['entity' => $mapAvailableActivity, 'id' => $mapAvailableActivity->getId()])
        ));
    }

    public function consumeMapAvailableActivity(ActivityEndEvent $event): void
    {
        $activity = $event->getActivity();
        if (!$activity instanceof ResourceGatheringActivity) {
            return;
        }

        $mapAvailableActivity = $activity->getMapAvailableActivity();
        $mapAvailableActivity->consume(1);
        if ($mapAvailableActivity->isEmpty()) {
            $this->mapAvailableActivityRepository->remove($mapAvailableActivity);
            return;
        }
        $this->mapAvailableActivityRepository->save($mapAvailableActivity);
    }

    public function continueUntillEmpty(ActivityEndEvent $event): void
    {
        $activity = $event->getActivity();
        if (!$activity instanceof ResourceGatheringActivity) {
            return;
        }

        $mapAvailableActivity = $activity->getMapAvailableActivity();
        if ($mapAvailableActivity->getQuantity() > 0) {
            $this->activityEngine->run($event->getSubject(), new ResourceGatheringActivity($mapAvailableActivity));
        }
    }
}