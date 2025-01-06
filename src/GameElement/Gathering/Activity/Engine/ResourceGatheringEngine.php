<?php

namespace App\GameElement\Gathering\Activity\Engine;

use App\Core\Engine;
use App\Entity\ActivityStep;
use App\Entity\Data\Activity;
use App\Entity\Data\MapAvailableActivity;
use App\Entity\Data\PlayerCharacter;
use App\GameElement\Activity\Engine\AbstractActivityEngine;
use App\GameElement\Crafting\Reward\ItemReward;
use App\GameElement\Gathering\Activity\ResourceGatheringActivity;
use App\GameElement\Gathering\Engine\ResourceCollection;
use App\GameElement\Reward\RewardPlayer;
use App\GameObject\Activity\ActivityType;
use App\GameObject\Reward\MasteryReward;
use App\GameTask\Message\BroadcastActivityStatusChange;
use App\GameTask\Message\ConsumeMapAvailableActivity;
use App\Repository\Data\ActivityRepository;
use DateTimeImmutable;
use Exception;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Twig\Environment;

#[AutoconfigureTag('game.engine.action')]
#[Engine(ResourceGatheringActivity::class)]
readonly class ResourceGatheringEngine extends AbstractActivityEngine
{
    public function __construct(
        private ResourceCollection  $resourceCollection,
        private ActivityRepository  $activityRepository,
        private MessageBusInterface $messageBus,
        private HubInterface        $hub,
        private Environment         $twig,
    )
    {
    }

    /**
     * @psalm-param PlayerCharacter $subject
     * @psalm-param  MapAvailableActivity $directObject
     * @throws Exception|ExceptionInterface
     */
    public function run(object $subject, object $directObject): void
    {
        $activity = (new Activity(ActivityType::RESOURCE_GATHERING));

        foreach ($this->generateSteps($subject, $directObject) as $generatedStep) {
            $activity->addStep($generatedStep);
        }

        $activity->applyMasteryPerformance($subject->getMasterySet());
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

            $this->onStepFinish($subject, $directObject, $step);

//            $step->setIsCompleted(true);
//            $this->repository->save($activity);

            $activity->progressStep();
            $this->activityRepository->save($activity);
        }

        $this->activityRepository->remove($activity);
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

    /**
     * @psalm-param PlayerCharacter $subject
     * @psalm-param  MapAvailableActivity $directObject
     */
    public function onStepFinish(object $subject, object $directObject, ActivityStep $step): void
    {
        $resource = $this->resourceCollection->get($directObject->getMapResource()->getResourceId());
        $this->messageBus->dispatch(new RewardPlayer($subject->getId(), new MasteryReward($resource->getInvolvedMastery(), 0.01)));
        $this->messageBus->dispatch(new RewardPlayer($subject->getId(), new ItemReward( $resource->getRewardItem(), 1)));
        $this->messageBus->dispatch(new ConsumeMapAvailableActivity($directObject->getId()));
        $this->hub->publish(new Update(
            'success_message_' . $subject->getId(),
            $this->twig->render('success_message.stream.html.twig', [
                'messages' => [
                    '+0.01 experience',
                    '+1 ' . $resource->getRewardItem()->getName(),
                ]
            ]
        )));
    }
}