<?php

namespace App\GameEngine\Action;

use App\Entity\ActivityStep;
use App\Entity\Data\Activity;
use App\Entity\Data\MapAvailableActivity;
use App\Entity\Data\PlayerCharacter;
use App\Entity\Mastery;
use App\GameEngine\Activity\ActivityType;
use App\GameEngine\Resource\ResourceCollection;
use App\GameTask\Message\BroadcastActivityStatusChange;
use App\GameTask\Message\ConsumeMapAvailableActivity;
use App\GameTask\Message\RewardItem;
use App\GameTask\Message\RewardMastery;
use App\Repository\Data\ActivityRepository;
use DateTimeImmutable;
use Exception;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;

#[AutoconfigureTag('game.engine.action')]
readonly class ResourceGatheringEngine extends AbstractActionEngine
{
    public function __construct(
        private ResourceCollection $resourceCollection,
        private ActivityRepository $activityRepository,
        private MessageBusInterface $messageBus,
    )
    {
    }

    /**
     * @psalm-param PlayerCharacter[] $who
     * @psalm-param  MapAvailableActivity $directObject
     * @throws Exception|ExceptionInterface
     */
    public function run(object $subject, object $directObject): void
    {
        $character = $subject;

        /** @var Resource $resource */
        $resource = $this->resourceCollection->get($directObject->getMapResource()->getResourceId())->getElement();
        $activity = (new Activity(ActivityType::RESOURCE_GATHERING))
            ->setMasteryInvolveds([new Mastery($resource->getInvolvedMastery(), $resource->getDifficulty())])
        ;

        for ($i = 0; $directObject->getQuantity() > $i; $i++) {
            $step = (new ActivityStep($resource->getGatheringTime()))
                ->addOnFinish(new RewardMastery($character->getId(), $resource->getInvolvedMastery(), 0.01))
                ->addOnFinish(new RewardItem($character->getId(), $resource->getRewardItemId(), 1))
                ->addOnFinish(new ConsumeMapAvailableActivity($directObject->getId()))
            ;

            $activity->addStep($step);
        }

        $activity->applyMasteryPerformance($character->getMasterySet());
        $directObject->setInvolvingActivity($activity);

        //TODO: lock player activity

        $activity->setStartedAt(new DateTimeImmutable());
        $this->activityRepository->save($activity);

        while ($step = $activity->getNextStep()) {
            $step->setScheduledAt(microtime(true));
            $this->activityRepository->save($activity);
            $this->messageBus->dispatch(new BroadcastActivityStatusChange($activity->getId()));

            $this->waitForStepFinish($step);

            $activity = $this->activityRepository->find($activity->getId());
            if (!$activity instanceof Activity) {
                return;
            }

//            $step->setIsCompleted(true);
//            $this->repository->save($activity);

            foreach ($step->getOnFinish() as $onFinish) {
                $this->messageBus->dispatch($onFinish);
            }
            $activity->progressStep();
            $this->activityRepository->save($activity);
        }

        $this->activityRepository->remove($activity);
    }

    private function waitForStepFinish(ActivityStep $step): void
    {
        $seconds = floor($step->getDuration());
        $microseconds = (int)bcmul(bcsub($step->getDuration(), $seconds, 4), 1000000, 0);
        sleep($seconds);
        usleep($microseconds);
    }

    public static function getId(): string
    {
        return self::class;
    }
}