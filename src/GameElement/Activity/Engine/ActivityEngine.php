<?php

namespace App\GameElement\Activity\Engine;
use App\GameElement\Activity\ActivityInterface;
use App\GameElement\Activity\Exception\ActivityNotAvailableException;
use Symfony\Component\Messenger\Exception\ExceptionInterface;

readonly class ActivityEngine
{

    public function __construct(
        private ActivityEngineCollection $actionEngineCollection,
    )
    {
    }

    /**
     * @throws \DateMalformedStringException
     * @throws ExceptionInterface
     */
    public function execute(object $subject, object $directObject, ActivityInterface $activity): void
    {
        // TODO: check if subject can do action
        // and if directObject is valid for the action

//        $reflectionClass = new ReflectionClass($directObject);
//        $availableActionAttributes = $reflectionClass->getAttributes(ActivityAvailable::class);
//        foreach ($availableActionAttributes as $availableActionAttribute) {
//            /** @var ActivityAvailable $availableActivity */
//            $availableActivity = $availableActionAttribute->newInstance();
//            if ($availableActivity->getId() === $activityId && $availableActivity->isAsDirectObject()) {
            $engine = $this->actionEngineCollection->getForAcvtivity($activity::class);
            $engine->run($subject, $directObject, $activity);
            return;
//            }
//        }

        throw new ActivityNotAvailableException("Activity $activityId is not available for " . $directObject::class);
    }
}