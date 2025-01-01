<?php

namespace App\GameEngine\Activity;
use App\GameElement\Action\ActionAvailable;
use App\GameEngine\Action\AbstractActionEngine;
use App\GameEngine\Action\ActionEngineCollection;
use App\GameEngine\Engine;
use ReflectionClass;

readonly class GameActivity
{

    public function __construct(
        private ActionEngineCollection $actionEngineCollection,
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
                $actionReflectionClass = new ReflectionClass($actionId);
                //TODO: check if action is available on object
                $gameEngine = $actionReflectionClass->getAttributes(Engine::class)[0]->newInstance();
                $engine = $this->actionEngineCollection->get($gameEngine->getId());
                $engine->run($subject, $directObject);
            }
        }

        //TODO: action not available on object error
    }
}