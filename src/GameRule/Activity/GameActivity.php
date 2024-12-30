<?php

namespace App\GameRule\Activity;
use App\GameElement\Action;
use App\GameObject\Action\AbstractAction;
use App\GameObject\ActionCollection;
use ReflectionClass;

readonly class GameActivity
{
    public function __construct(
        private ActionCollection $actionCollection,
    )
    {
    }

    /**
     */
    public function execute(array $whos, object $on, string $action): void
    {
        $reflectionClass = new ReflectionClass($on);
        $availableActionAttributes = $reflectionClass->getAttributes(Action::class);
        foreach ($availableActionAttributes as $availableActionAttribute) {
            /** @var Action $availableAction */
            $availableAction = $availableActionAttribute->newInstance();
            if ($availableAction->getClass() === $action) {
                /** @var AbstractAction $actionClass */
                $actionClass = $this->actionCollection->get($availableAction->getClass());
                $actionClass->execute($whos, $on);
            }
        }

        //TODO: action not available error
    }
}