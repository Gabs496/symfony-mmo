<?php

namespace App\GameRule\Activity;
use App\GameElement\Action\ActionAvailable;
use App\GameObject\Action\AbstractActionEngine;
use App\GameObject\ActionEngineCollection;
use Exception;
use ReflectionClass;

readonly class GameActivity
{
    public function __construct(
        private ActionEngineCollection $actionCollection,
    )
    {
    }

    public function execute(object $subject, object $directObject, string $actionId): void
    {
        // TODO: check if subject can do action

        $reflectionClass = new ReflectionClass($directObject);
        $availableActionAttributes = $reflectionClass->getAttributes(ActionAvailable::class);
        foreach ($availableActionAttributes as $availableActionAttribute) {
            /** @var ActionAvailable $availableAction */
            $availableAction = $availableActionAttribute->newInstance();
            if ($availableAction->getId() === $actionId && $availableAction->isAsDirectObject()) {
                /** @var AbstractActionEngine $engine */
                $engine = $this->actionCollection->getEngineFor($availableAction->getId());
                $engine->run($subject, $directObject);
            }
        }

        //TODO: action not available on object error
    }
}