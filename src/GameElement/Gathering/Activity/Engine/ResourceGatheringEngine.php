<?php

namespace App\GameElement\Gathering\Activity\Engine;

use App\Entity\Data\MapAvailableActivity;
use App\Entity\Data\PlayerCharacter;
use App\GameElement\Activity\ActivityStep;
use App\GameElement\Activity\Engine\AbstractActivityEngine;
use App\GameElement\Core\EngineFor;
use App\GameElement\Gathering\Activity\ResourceGatheringActivity;
use App\GameElement\Gathering\Engine\ResourceCollection;
use App\Repository\Data\ActivityRepository;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\Messenger\MessageBusInterface;

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

    public function onStepStart(object $subject, object $directObject): void
    {
    }
}