<?php

namespace App\GameObject;

use App\GameObject\Map\AbstractMapObject;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

readonly class MapCollection extends AbstractGameObjectCollection
{
    public function __construct(
        #[AutowireIterator('game.map')]
        protected iterable $gameObjects,
    ) {
    }

    /** @psalm-return AbstractMapObject */
    public function get(string $id): AbstractGameObject
    {
        return parent::get($id);
    }
}