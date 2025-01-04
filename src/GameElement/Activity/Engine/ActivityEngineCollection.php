<?php

namespace App\GameElement\Activity\Engine;

use App\Core\Engine;
use InvalidArgumentException;
use ReflectionClass;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

readonly class ActivityEngineCollection
{
    public function __construct(
        /** @var AbstractActivityEngine[] $activityEngines */
        #[AutowireIterator('game.engine.action')]
        protected iterable $activityEngines,
    ) {
    }

    public function get(string $id): AbstractActivityEngine
    {
        foreach ($this->activityEngines as $activityEngine) {
            if ($activityEngine->getId() === $id) {
                return $activityEngine;
            }
        }

        throw new InvalidArgumentException("Action engine with id $id not found");
    }

    public function getForAcvtivity(string $activityId): AbstractActivityEngine
    {
        foreach ($this->activityEngines as $activityEngine) {
            $activityEngineReflection = new ReflectionClass($activityEngine);
            $engineAttributes = $activityEngineReflection->getAttributes(Engine::class);
            foreach ($engineAttributes as $engineAttribute) {
                $activity = $engineAttribute->newInstance();
                if ($activity->getId() === $activityId) {
                    return $activityEngine;
                }
            }
        }

        throw new InvalidArgumentException("Engine for activity $activityId not found");
    }
}