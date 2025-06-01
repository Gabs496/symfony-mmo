<?php

namespace App\GameElement\Gathering\Activity;

use App\Entity\Game\MapObject;
use App\GameElement\Activity\Engine\ActivityEngineExtensionInterface;
use App\GameElement\Activity\Event\ActivityEndEvent;
use App\GameElement\Activity\Event\ActivityTimeoutEvent;
use App\GameElement\Core\GameObject\GameObjectEngine;
use App\GameElement\Core\Token\TokenEngine;
use App\GameElement\Crafting\AbstractRecipe;
use App\GameElement\Gathering\Event\ResourceGatheringEndedEvent;
use App\GameElement\Gathering\Event\ResourceGatheringEvent;
use App\GameElement\Health\Component\Health;
use App\GameElement\Health\Engine\HealthEngine;
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
        protected TokenEngine $tokenEngine,
        protected HealthEngine $healthEngine,
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

        /** @var MapObject $resource */
        $resource = $this->tokenEngine->exchange($activity->getResourceToken());
        $activity->setResource($resource);

        if ($resource->hasComponent(Health::class)) {
            $this->healthEngine->decreaseCurrentHealth($resource, 1.0);
        }

        $health = $resource->getComponent(Health::class);
        $isDepealed = !$health || !$health->isAlive();
        if ($isDepealed) {
            $this->mapObjectRepository->remove($resource);
        } else {
            $this->mapObjectRepository->save($resource);
        }

        $this->eventDispatcher->dispatch(new ResourceGatheringEvent($activity));

        $this->handleReward($activity);
    }

    protected function handleReward(ResourceGatheringActivity $activity): void
    {
        /** @var AbstractRecipe $recipePrototype */
        $recipePrototype = $this->gameObjectEngine->getPrototype($activity->getResource()->getObjectId());
        foreach ($recipePrototype->getRewards() as $reward) {
            $this->rewardEngine->apply(new RewardApply($reward, $activity->getSubject()));
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