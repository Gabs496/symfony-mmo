<?php

namespace App\GameElement\Gathering\Engine;

use App\GameElement\Activity\Engine\ActivityEngine;
use App\GameElement\Core\GameObject\GameObjectInterface;
use App\GameElement\Gathering\Activity\ResourceGatheringActivity;
use App\GameElement\Gathering\Component\GatheringComponent;
use App\GameElement\Gathering\Event\ResourceGatheredEvent;
use App\GameElement\Gathering\Event\ResourceGatheringEndedEvent;
use App\GameElement\Item\Component\StackComponent;
use App\GameElement\Reward\Engine\RewardEngine;
use App\GameElement\Reward\RewardApply;
use App\Repository\Game\GameObjectRepository;
use App\Repository\Game\MapObjectRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

readonly class GatheringEngine
{

    public function __construct(
        private GameObjectRepository     $gameObjectRepository,
        private MapObjectRepository      $mapObjectRepository,
        private RewardEngine             $rewardEngine,
        private EventDispatcherInterface $eventDispatcher,
        private ActivityEngine           $activityEngine,
    )
    {

    }

    public function startGathering(GameObjectInterface $subject, GameObjectInterface $resource): void
    {
        $this->activityEngine->run(new ResourceGatheringActivity($subject, $resource));
    }

    public function gather(GameObjectInterface $subject, GameObjectInterface $gameObject, float $quantity = 1.0): void
    {
        $newObject = $this->take($gameObject, $quantity);
        $this->eventDispatcher->dispatch(new ResourceGatheredEvent($subject, $newObject));
        $this->handleReward($subject, $newObject);
        $this->eventDispatcher->dispatch(new ResourceGatheringEndedEvent($subject, $newObject));
    }

    private function take(GameObjectInterface $gameObject, float $quantity): ?GameObjectInterface
    {
        $stack = $gameObject->getComponent(StackComponent::class);

        if (!$stack) {
            $this->gameObjectRepository->remove($gameObject);
            return null;
        }

        $newObject = $gameObject->clone();
        $newObject->setComponent(new StackComponent(min($quantity,$stack->getCurrentQuantity()), 99));

        $stack->decreaseBy($quantity);
        $isDepealed = $stack->getCurrentQuantity() <= 0;
        if ($isDepealed) {
            $this->mapObjectRepository->remove($this->mapObjectRepository->findOneBy(['gameObject' => $gameObject]));
            $this->gameObjectRepository->remove($gameObject);
        } else {
            $this->gameObjectRepository->save($gameObject);
        }

        return $newObject;
    }

    public function handleReward(GameObjectInterface $subject, GameObjectInterface $resource): void
    {
        $gathering = $resource->getComponent(GatheringComponent::class);
        foreach ($gathering->getRewards() as $reward) {
            $this->rewardEngine->apply($reward, $subject);
        }
    }
}