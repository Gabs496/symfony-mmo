<?php

namespace App\GameElement\Gathering\Activity;

use App\Entity\Data\PlayerCharacter;
use App\GameElement\Activity\ActivityInterface;
use App\GameElement\Activity\ActivityStep;
use App\GameElement\Activity\Engine\ActivityEngineExtensionInterface;
use App\GameElement\Activity\Event\ActivityStepEndEvent;
use App\GameElement\Activity\Event\BeforeActivityStartEvent;
use App\GameElement\Gathering\Engine\ResourceCollection;
use App\GameElement\Reward\RewardApply;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Messenger\MessageBusInterface;

readonly class ResourceGatheringEngineExtension implements ActivityEngineExtensionInterface
{
    public function __construct(
        private ResourceCollection     $resourceCollection,
        private MessageBusInterface $messageBus,
    )
    {
    }

    /**
     * @param PlayerCharacter $subject
     * @param  ResourceGatheringActivity $activity
     */
    public function generateSteps(object $subject, ActivityInterface $activity): iterable
    {
        $resource = $this->resourceCollection->get($activity->getMapAvailableActivity()->getMapResource()->getResourceId());
        for ($i = 0; $activity->getMapAvailableActivity()->getQuantity() > $i; $i++) {
            $step = new ActivityStep($resource->getGatheringTime());
            yield $step;
        }
    }

    #[AsEventListener(BeforeActivityStartEvent::class)]
    public function beforeActivityStartEvent(BeforeActivityStartEvent $event): void
    {
        $activity = $event->getActivity();
        if (!$activity instanceof ResourceGatheringActivity) {
            return;
        }

        foreach ($this->generateSteps($event->getSubject(), $activity) as $generatedStep) {
            $event->getActivityEntity()->addStep($generatedStep);
        }
    }

    #[AsEventListener(ActivityStepEndEvent::class)]
    public function onActivityStepEnd(ActivityStepEndEvent $event): void
    {
        $activity = $event->getActivity();
        if (!$activity instanceof ResourceGatheringActivity) {
            return;
        }

        foreach ($activity->getRewards() as $reward) {
            $this->messageBus->dispatch(new RewardApply($reward, $event->getSubject()));
        }
    }
}