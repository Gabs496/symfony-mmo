<?php

namespace App\GameEngine\Action;

use InvalidArgumentException;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

readonly class ActionEngineCollection
{
    public function __construct(
        /** @var AbstractActionEngine[] $actionEngines */
        #[AutowireIterator('game.engine.action')]
        protected iterable $actionEngines,
    ) {
    }

    public function get(string $id): AbstractActionEngine
    {
        foreach ($this->actionEngines as $actionEngine) {
            if ($actionEngine->getId() === $id) {
                return $actionEngine;
            }
        }

        throw new InvalidArgumentException("Action engine with id $id not found");
    }
}