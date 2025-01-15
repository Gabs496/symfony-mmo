<?php

namespace App\Engine\MapAvailableActivity\Event\Handler;

use App\Engine\MapAvailableActivity\Event\ConsumeMapAvailableActivity;
use App\Entity\Data\Activity;
use App\Entity\Data\MapAvailableActivity;
use App\GameElement\Activity\Event\ActivityEnded;
use App\GameElement\Activity\Event\ActivityStarted;
use App\Repository\Data\ActivityRepository;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;
use Twig\Environment;

readonly class ActivityHandler
{
    public function __construct(
        private ActivityRepository $activityRepository,
        private HubInterface       $hub,
        private Environment        $twig,
        private MessageBusInterface $messageBus,
    )
    {
    }

    #[AsMessageHandler]
    public function onActivityStart(ActivityStarted $event): void
    {
        if (!$event->getSubject() instanceof MapAvailableActivity) {
            return;
        }

        $activity = $this->activityRepository->find($event->getActivityId());
        if (!$activity instanceof Activity) {
            return;
        }

        foreach ($activity->getMapAvailableActivities() as $mapAvailableActivity) {
            $this->hub->publish(new Update(['mapAvailableActivities_' . $mapAvailableActivity->getMapId()],
                $this->twig->load('map/MapAvailableActivity.stream.html.twig')->renderBlock('update', ['entity' => $mapAvailableActivity, 'id' => $mapAvailableActivity->getId()])
            ));
        }
    }

    #[AsMessageHandler]
    public function onActivityEnd(ActivityEnded $event): void
    {
        $this->messageBus->dispatch(new ConsumeMapAvailableActivity($event->getSubject()->getId()));

    }
}