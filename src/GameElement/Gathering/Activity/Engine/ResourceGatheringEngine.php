<?php

namespace App\GameElement\Gathering\Activity\Engine;

use App\Engine\MapAvailableActivity\Event\ConsumeMapAvailableActivity;
use App\Entity\Data\MapAvailableActivity;
use App\Entity\Data\PlayerCharacter;
use App\GameElement\Activity\ActivityStep;
use App\GameElement\Activity\Engine\AbstractActivityEngine;
use App\GameElement\Activity\Event\ActivityStepEndEvent;
use App\GameElement\Core\EngineFor;
use App\GameElement\Gathering\Activity\ResourceGatheringActivity;
use App\GameElement\Gathering\Engine\ResourceCollection;
use App\Repository\Data\ActivityRepository;
use App\Repository\Data\MapAvailableActivityRepository;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Messenger\MessageBusInterface;

#[AutoconfigureTag('game.engine.action')]
#[EngineFor(ResourceGatheringActivity::class)]
readonly class ResourceGatheringEngine extends AbstractActivityEngine
{
    public function __construct(
        private ResourceCollection     $resourceCollection,
        ActivityRepository             $activityRepository,
        MessageBusInterface            $messageBus,
        EventDispatcherInterface       $eventDispatcher,
        private MapAvailableActivityRepository $mapAvailableActivityRepository,
    )
    {
        parent::__construct($activityRepository, $messageBus, $eventDispatcher);
    }

    public static function getId(): string
    {
        return self::class;
    }

    /**
     * @psalm-param PlayerCharacter $subject
     * @psalm-param  MapAvailableActivity $directObject
     */
    public function generateSteps(object $subject, object $directObject): iterable
    {
        $resource = $this->resourceCollection->get($directObject->getMapResource()->getResourceId());
        for ($i = 0; $directObject->getQuantity() > $i; $i++) {
            $step = new ActivityStep($resource->getGatheringTime());
            yield $step;
        }
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
}