<?php

namespace App\GameTask\Handler;

use App\Entity\Data\Activity;
use App\GameTask\Message\BroadcastActivityStatusChange;
use App\Repository\Data\ActivityRepository;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Twig\Environment;

#[AsMessageHandler]
readonly class BroadcastActivityStatusChangeHandler
{
    public function __construct(
        private ActivityRepository $activityRepository,
        private HubInterface $hub,
        private Environment $twig,
    )
    {
    }

    public function __invoke(BroadcastActivityStatusChange $message): void
    {
        $activity = $this->activityRepository->find($message->getActivityId());
        if (!$activity instanceof Activity) {
            return;
        }

        foreach ($activity->getMapAvailableActivities() as $mapAvailableActivity) {
            $this->hub->publish(new Update(['mapAvailableActivities_' . $mapAvailableActivity->getMap()->getId()],
                $this->twig->load('broadcast/Data/MapAvailableActivity.stream.html.twig')->renderBlock('update', ['entity' => $mapAvailableActivity, 'id' => $mapAvailableActivity->getId()])
            ));
        }
    }
}