<?php

namespace App\GameElement\Gathering\Engine;

use App\GameElement\Activity\Engine\ActivityEngine;
use App\GameElement\Core\GameObject\GameObjectInterface;
use App\GameElement\Gathering\Activity\ResourceGatheringActivity;
use App\GameElement\Gathering\Component\ResourceComponent;
use App\GameElement\Gathering\Event\ResourceGatheredEvent;
use App\GameElement\Gathering\Event\ResourceGatheringEndedEvent;
use App\GameElement\Item\Component\ItemComponent;
use App\GameElement\Reward\Engine\RewardEngine;
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
        $item = $gameObject->getComponent(ItemComponent::class);

        if (!$item->getQuantity()) {
            $this->gameObjectRepository->remove($gameObject);
            return null;
        }

        $newObject = $gameObject->clone();
        $itemComponent = $newObject->getComponent(ItemComponent::class);
        $itemComponent->setQuantity(min($quantity,$item->getQuantity()));

        $item->decreaseBy($quantity);
        $isDepealed = $item->getQuantity() <= 0;
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
        $gathering = $resource->getComponent(ResourceComponent::class);
        foreach ($gathering->getRewards() as $reward) {
            $this->rewardEngine->apply($reward, $subject);
        }
    }
}