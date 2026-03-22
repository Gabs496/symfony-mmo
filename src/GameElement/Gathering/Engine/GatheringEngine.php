<?php

namespace App\GameElement\Gathering\Engine;

use App\GameElement\Activity\Engine\ActivityEngine;
use App\GameElement\Gathering\Activity\ResourceGatheringActivity;
use App\GameElement\Gathering\Component\ResourceStatus;
use App\GameElement\Gathering\Event\ResourceGatheredEvent;
use App\GameElement\Gathering\Event\ResourceGatheringEndedEvent;
use App\GameElement\Gathering\Exception\ResourceDepealedException;
use App\GameElement\Gathering\GatherRewardsInterface;
use App\GameElement\Reward\Engine\RewardEngine;
use Doctrine\ORM\EntityManagerInterface;
use PennyPHP\Core\Engine\GameObjectEngine;
use PennyPHP\Core\GameObjectInterface;
use PennyPHP\Core\Repository\GameObjectRepository;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

readonly class GatheringEngine
{

    public function __construct(
        private GameObjectRepository     $gameObjectRepository,
        private RewardEngine             $rewardEngine,
        private EventDispatcherInterface $eventDispatcher,
        private ActivityEngine           $activityEngine,
        private GameObjectEngine         $gameObjectEngine,
        private EntityManagerInterface   $entityManager,
    )
    {

    }

    public function startGathering(GameObjectInterface $subject, GameObjectInterface $resource): void
    {
        $this->activityEngine->run(new ResourceGatheringActivity($subject, $resource));
    }

    public function gather(GameObjectInterface $subject, GameObjectInterface $resource): void
    {
        try {
            $this->depeal($resource);
            $this->eventDispatcher->dispatch(new ResourceGatheredEvent($subject, $resource));
            $this->entityManager->flush();
            $this->eventDispatcher->dispatch(new ResourceGatheringEndedEvent($subject, $resource));
        } catch (ResourceDepealedException) {
            $this->gameObjectRepository->remove($resource);
        }

    }

    /**
     * @throws ResourceDepealedException
     */
    protected function depeal(GameObjectInterface $gameObject): void
    {
        $resource = $gameObject->getComponent(ResourceStatus::class);

        if (!$resource->getAvailability()) {
            throw new ResourceDepealedException();
        }

        $resource->decreaseAvailability();
        $isDepealed = $resource->getAvailability() <= 0;
        if ($isDepealed) {
            $this->entityManager->remove($gameObject);
        }

        return;
    }

    #[AsEventListener(ResourceGatheredEvent::class)]
    public function handleRewards(ResourceGatheredEvent $event): void
    {
        $subject = $event->getSubject();
        $resource = $event->getResource();
        $objectHandler = $this->gameObjectEngine->getPrototype($resource->getPrototype());

        if (!$objectHandler instanceof GatherRewardsInterface) {
            return;
        }

        foreach ($objectHandler->getGatherRewards() as $reward) {
            $this->rewardEngine->apply($reward, $subject);
        }
    }
}