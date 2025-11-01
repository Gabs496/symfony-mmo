<?php

namespace App\GameElement\Gathering\Activity;

use App\Entity\Game\GameObject;
use App\GameElement\Activity\AbstractActivity;
use App\GameElement\Activity\Engine\ActivityEngineExtensionInterface;
use App\GameElement\Core\GameObject\GameObjectEngine;
use App\GameElement\Core\Token\TokenEngine;
use App\GameElement\Crafting\AbstractRecipe;
use App\GameElement\Gathering\Event\ResourceGatheringEndedEvent;
use App\GameElement\Gathering\Event\ResourceGatheringEvent;
use App\GameElement\Health\Component\Health;
use App\GameElement\Health\Engine\HealthEngine;
use App\GameElement\Reward\Engine\RewardEngine;
use App\GameElement\Reward\RewardApply;
use App\Repository\Game\GameObjectRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/** @extends ActivityEngineExtensionInterface<ResourceGatheringActivity> */
readonly class ResourceGatheringEngineExtension implements ActivityEngineExtensionInterface
{
    public function __construct(
        private EventDispatcherInterface $eventDispatcher,
        private RewardEngine $rewardEngine,
        private GameObjectEngine $gameObjectEngine,
        private GameObjectRepository $gameObjectRepository,
        private TokenEngine $tokenEngine,
        private HealthEngine $healthEngine,
    )
    {
    }

    public function supports(AbstractActivity $activity): bool
    {
        return $activity instanceof ResourceGatheringActivity;
    }

    public function getDuration(AbstractActivity $activity): float
    {
        return $activity->getGathering()->getGatheringTime();
    }

    public function beforeStart(AbstractActivity $activity): void
    {
        return;
    }

    public function onComplete(AbstractActivity $activity): void
    {
        $this->consume($activity);
        $this->eventDispatcher->dispatch(new ResourceGatheringEvent($activity));
        $this->handleReward($activity);
    }

    public function onFinish(AbstractActivity $activity): void
    {
        $this->eventDispatcher->dispatch(new ResourceGatheringEndedEvent($activity));
    }

    public function cancel(AbstractActivity $activity): void
    {
        // TODO: Implement cancel() method.
    }

    protected function consume(ResourceGatheringActivity $activity): void
    {
        /** @var GameObject $gameObject */
        $gameObject = $this->tokenEngine->exchange($activity->getResourceToken());
        $activity->setResource($gameObject);

        $health = $gameObject->getComponent(Health::class);
        if ($health) {
            $this->healthEngine->decreaseCurrentHealth($gameObject, 1.0);
        }

        $isDepealed = !$health || !$health->isAlive();
        if ($isDepealed) {
            $this->gameObjectRepository->remove($gameObject);
        } else {
            $this->gameObjectRepository->save($gameObject);
        }
    }

    protected function handleReward(ResourceGatheringActivity $activity): void
    {
        /** @var AbstractRecipe $recipePrototype */
        $recipePrototype = $this->gameObjectEngine->getPrototype($activity->getResource()->getType());
        foreach ($recipePrototype->getRewards() as $reward) {
            $this->rewardEngine->apply(new RewardApply($reward, $activity->getSubject()));
        }
    }
}