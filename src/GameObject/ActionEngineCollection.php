<?php

namespace App\GameObject;

use App\GameObject\Action\AbstractActionEngine;
use Exception;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

readonly class ActionEngineCollection extends AbstractGameObjectCollection
{
    /** @psalm-var AbstractActionEngine[] $gameObjects */
    public function __construct(
        #[AutowireIterator('game.action')]
        protected iterable $gameObjects,
    ) {
    }

    /** @psalm-return AbstractActionEngine */
    public function get(string $id): AbstractGameObject
    {
        return parent::get($id);
    }

    /**
     * @throws Exception
     */
    public function getEngineFor(string $actionId): AbstractActionEngine
    {
       foreach ($this->gameObjects as $gameObject) {
           if ($gameObject->getActionId() === $actionId) {
               return $gameObject;
           }
       }

       throw new Exception(sprintf('Engine for action %s not found', $actionId));
    }
}