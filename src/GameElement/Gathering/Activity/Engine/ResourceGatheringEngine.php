<?php

namespace App\GameElement\Gathering\Activity\Engine;

use App\Entity\Data\MapAvailableActivity;
use App\Entity\Data\PlayerCharacter;
use App\GameElement\Activity\ActivityStep;
use App\GameElement\Activity\Engine\AbstractActivityEngine;
use App\GameElement\Core\EngineFor;
use App\GameElement\Gathering\Activity\ResourceGatheringActivity;
use App\GameElement\Gathering\Engine\ResourceCollection;
use App\GameElement\Item\Reward\ItemReward;
use App\GameElement\Mastery\MasteryReward;
use App\GameElement\Reward\RewardApply;
use App\GameTask\Message\ConsumeMapAvailableActivity;
use App\Repository\Data\ActivityRepository;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\Messenger\MessageBusInterface;
use Throwable;

#[AutoconfigureTag('game.engine.action')]
#[EngineFor(ResourceGatheringActivity::class)]
readonly class ResourceGatheringEngine extends AbstractActivityEngine
{
    public function __construct(
        private ResourceCollection  $resourceCollection,
        ActivityRepository  $activityRepository,
        MessageBusInterface $messageBus,
    )
    {
        parent::__construct($activityRepository, $messageBus);
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
     * @throws Throwable
     */
    public function onStepFinish(object $subject, object $directObject, ActivityStep $step): void
    {
        $resource = $this->resourceCollection->get($directObject->getMapResource()->getResourceId());
        $rewards = [
            new MasteryReward($resource->getInvolvedMastery(), 0.01),
            new ItemReward( $resource->getRewardItem(), 1),
        ];
        foreach ($rewards as $reward) {
            $this->messageBus->dispatch(new RewardApply($reward, $subject));
        }
        $this->messageBus->dispatch(new ConsumeMapAvailableActivity($directObject->getId()));
    }

    public function onStepStart(object $subject, object $directObject): void
    {
    }
}