<?php

namespace App\GameElement\Gathering\Activity;

use App\GameElement\Activity\Engine\ActivityEngineExtensionInterface;
use App\GameElement\Activity\Event\ActivityEndEvent;
use App\GameElement\Activity\Event\ActivityTimeoutEvent;
use App\GameElement\Core\GameObject\GameObjectEngine;
use App\GameElement\Crafting\AbstractRecipe;
use App\GameElement\Gathering\Event\ResourceGatheringEndedEvent;
use App\GameElement\Gathering\Event\ResourceGatheringEvent;
use App\GameElement\Reward\Engine\RewardEngine;
use App\GameElement\Reward\RewardApply;
use App\Repository\Game\MapObjectRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

readonly class ResourceGatheringEngineExtension implements ActivityEngineExtensionInterface, EventSubscriberInterface
{
    public function __construct(
        protected EventDispatcherInterface $eventDispatcher,
        protected RewardEngine $rewardEngine,
        protected GameObjectEngine $gameObjectEngine,
        protected MapObjectRepository $mapObjectRepository,
    )
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ActivityTimeoutEvent::class => [
                ['dispatchGathering', 0]
            ],
            ActivityEndEvent::class => [
                ['reward', 0],
                ['dispatchEnd', 0]
            ],
        ];
    }

    public function dispatchGathering(ActivityTimeoutEvent $event): void
    {
        $activity = $event->getActivity();
        if (!$activity instanceof ResourceGatheringActivity) {
            return;
        }

        $resource = $this->mapObjectRepository->find($activity->getResource()->getId());
        $this->mapObjectRepository->remove($resource);


        $this->eventDispatcher->dispatch(new ResourceGatheringEvent($activity));

        //        $mapSpawnedResource
//            ->consume(1)
//            ->endActivity()
//        ;
//        if ($mapSpawnedResource->isEmpty()) {
//            $this->mapObjectRepository->remove($mapSpawnedResource);
//            return;
//        }
//        $this->mapObjectRepository->save($mapSpawnedResource);
    }

    public function reward(ActivityEndEvent $event): void
    {
        $activity = $event->getActivity();
        if (!$activity instanceof ResourceGatheringActivity) {
            return;
        }

        /** @var AbstractRecipe $recipePrototype */
        $recipePrototype = $this->gameObjectEngine->getPrototype($activity->getResource()->getObjectId());
        foreach ($recipePrototype->getRewards() as $reward) {
            $this->rewardEngine->apply(new RewardApply($reward, $event->getActivity()->getSubject()));
        }
    }

    public function dispatchEnd(ActivityEndEvent $event): void
    {
        $activity = $event->getActivity();
        if (!$activity instanceof ResourceGatheringActivity) {
            return;
        }

        $this->eventDispatcher->dispatch(new ResourceGatheringEndedEvent($activity));
    }
}