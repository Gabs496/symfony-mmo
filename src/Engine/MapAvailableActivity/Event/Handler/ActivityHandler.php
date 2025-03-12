<?php

namespace App\Engine\MapAvailableActivity\Event\Handler;

use App\GameElement\Activity\Event\ActivityEndEvent;
use App\GameElement\Activity\Event\ActivityStartEvent;
use App\GameElement\Activity\Event\ActivityStepEndEvent;
use App\GameElement\Activity\Event\ActivityStepStartEvent;
use App\GameElement\Gathering\Activity\ResourceGatheringActivity;
use App\Repository\Data\MapAvailableActivityRepository;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Twig\Environment;

readonly class ActivityHandler
{
    public function __construct(
        private HubInterface       $hub,
        private Environment        $twig,
        private MapAvailableActivityRepository $mapAvailableActivityRepository,
    )
    {
    }

    #[AsEventListener(ActivityStartEvent::class)]
    public function onActivityStart(ActivityStartEvent $event): void
    {
        $activity = $event->getActivity();
        if (!$activity instanceof ResourceGatheringActivity) {
            return;
        }

        $activity->getMapAvailableActivity()->startActivity($event->getActivityEntity());
    }

    #[AsEventListener(ActivityStepStartEvent::class)]
    public function onActivityStepStart(ActivityStepStartEvent $event): void
    {
        $activity = $event->getActivity();
        if (!$activity instanceof ResourceGatheringActivity) {
            return;
        }

        $mapAvailableActivity = $activity->getMapAvailableActivity();
        $this->hub->publish(new Update(['mapAvailableActivities_' . $mapAvailableActivity->getMapId()],
            $this->twig->load('map/MapAvailableActivity.stream.html.twig')->renderBlock('update', ['entity' => $mapAvailableActivity, 'id' => $mapAvailableActivity->getId()])
        ));
    }

    #[AsEventListener(ActivityStepEndEvent::class)]
    public function onActivityStepEnd(ActivityStepEndEvent $event): void
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

    #[AsEventListener(ActivityEndEvent::class)]
    public function onActivityEnd(ActivityEndEvent $event): void
    {
        $activity = $event->getActivity();
        if (!$activity instanceof ResourceGatheringActivity) {
            return;
        }

        $activity->getMapAvailableActivity()->endActivity($event->getActivityEntity());
    }
}