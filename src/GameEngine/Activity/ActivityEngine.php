<?php

namespace App\GameEngine\Activity;
use App\GameElement\Activity\ActivityAvailable;
use App\GameEngine\Engine;
use ReflectionClass;

readonly class ActivityEngine
{

    public function __construct(
        private ActivityEngineCollection $actionEngineCollection,
    )
    {
    }

    public function execute(object $subject, object $directObject, string $activityId): void
    {
        // TODO: check if subject can do action

        $reflectionClass = new ReflectionClass($directObject);
        $availableActionAttributes = $reflectionClass->getAttributes(ActivityAvailable::class);
        foreach ($availableActionAttributes as $availableActionAttribute) {
            /** @var ActivityAvailable $availableActivity */
            $availableActivity = $availableActionAttribute->newInstance();
            if ($availableActivity->getId() === $activityId && $availableActivity->isAsDirectObject()) {
                $engine = $this->actionEngineCollection->getForAcvtivity($activityId);
                $engine->run($subject, $directObject);
            }
        }

        //TODO: action not available on object error
    }
}