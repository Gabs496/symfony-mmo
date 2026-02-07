<?php

namespace App\GameElement\Gathering\Engine;

use App\GameElement\Activity\Engine\ActivityEngine;
use PennyPHP\Core\GameObject\Engine\GameObjectEngine;
use PennyPHP\Core\GameObject\Entity\GameObject;
use PennyPHP\Core\GameObject\GameObjectInterface;
use App\GameElement\Gathering\Activity\ResourceGatheringActivity;
use App\GameElement\Gathering\Component\AttachedResourceComponent;
use App\GameElement\Gathering\Event\ResourceGatheredEvent;
use App\GameElement\Gathering\Event\ResourceGatheringEndedEvent;
use App\GameElement\Gathering\GatherableInterface;
use App\GameElement\Item\Component\ItemComponent;
use App\GameElement\Reward\Engine\RewardEngine;
use App\Repository\Game\GameObjectRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

readonly class GatheringEngine
{

    public function __construct(
        private GameObjectRepository     $gameObjectRepository,
        private RewardEngine             $rewardEngine,
        private EventDispatcherInterface $eventDispatcher,
        private ActivityEngine           $activityEngine, private GameObjectEngine $gameObjectEngine,
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

    protected function take(GameObject $gameObject, float $quantity): ?GameObjectInterface
    {
        $resource = $gameObject->getComponent(AttachedResourceComponent::class);

        if (!$resource->getAvailability()) {
            $this->gameObjectRepository->remove($gameObject);
            return null;
        }

        $newObject = $gameObject->clone();
        $newObject->removeComponent(AttachedResourceComponent::class);
        $itemComponent = $newObject->getComponent(ItemComponent::class);
        $itemComponent->setQuantity(min($quantity,$resource->getAvailability()));

        $resource->decreaseAvailability($quantity);
        $isDepealed = $resource->getAvailability() <= 0;
        if ($isDepealed) {
            $this->gameObjectRepository->remove($gameObject);
        } else {
            $this->gameObjectRepository->save($gameObject);
        }

        return $newObject;
    }

    protected function handleReward(GameObject $subject, GameObject $resource): void
    {
        $objectHandler = $this->gameObjectEngine->getPrototype($resource->getPrototype());

        if (!$objectHandler instanceof GatherableInterface) {
            return;
        }

        foreach ($objectHandler->getGatherRewards() as $reward) {
            $this->rewardEngine->apply($reward, $subject);
        }
    }
}