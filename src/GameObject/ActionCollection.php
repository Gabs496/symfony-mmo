<?php

namespace App\GameObject;

use App\GameObject\Action\AbstractAction;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

readonly class ActionCollection extends AbstractGameObjectCollection
{
    public function __construct(
        #[AutowireIterator('game.action')]
        protected iterable $gameObjects,
    ) {
    }

    /** @psalm-return AbstractAction */
    public function get(string $id): AbstractGameObject
    {
        return parent::get($id);
    }
}