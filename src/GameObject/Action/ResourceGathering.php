<?php

namespace App\GameObject\Action;

use App\Entity\ActivityStep;
use App\Entity\Data\Activity;
use App\Entity\Data\MapAvailableActivity;
use App\Entity\Data\PlayerCharacter;
use App\Entity\Mastery;
use App\GameObject\ResourceCollection;
use App\GameRule\Activity\ActivityType;
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

#[AutoconfigureTag('game.action')]
readonly class ResourceGathering extends AbstractAction
{
    public function __construct(
        private ResourceCollection $resourceCollection,
        private ActivityRepository $activityRepository,
        private MessageBusInterface $messageBus,
    )
    {
        parent::__construct('gather');
    }

    /**
     * @psalm-param PlayerCharacter[] $who
     * @psalm-param  MapAvailableActivity $on
     * @throws Exception|ExceptionInterface
     */
    public function execute(array $whos, object $on): void
    {

        if (count($whos) !== 1) {
            throw new Exception('Only one character can gather resources');
        }
        $character = $whos[0];

        /** @var Resource $resource */
        $resource = $this->resourceCollection->get($on->getMapResource()->getResourceId())->getElement();
        $activity = (new Activity(ActivityType::RESOURCE_GATHERING))
            ->setMasteryInvolveds([new Mastery($resource->getInvolvedMastery(), $resource->getDifficulty())])
        ;

        for ($i = 0; $on->getQuantity() > $i; $i++) {
            $step = (new ActivityStep($resource->getGatheringTime()))
                ->addOnFinish(new RewardMastery($character->getId(), $resource->getInvolvedMastery(), 0.01))
                ->addOnFinish(new RewardItem($character->getId(), $resource->getRewardItemId(), 1))
                ->addOnFinish(new ConsumeMapAvailableActivity($on->getId()))
            ;

            $activity->addStep($step);
        }

        $activity->applyMasteryPerformance($character->getMasterySet());
        $on->setInvolvingActivity($activity);

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