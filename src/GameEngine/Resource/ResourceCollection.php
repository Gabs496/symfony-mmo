<?php

namespace App\GameEngine\Resource;

use App\GameEngine\AbstractGameObjectCollection;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

readonly class ResourceCollection extends AbstractGameObjectCollection
{
    public function __construct(
        #[AutowireIterator('game.resource')]
        protected iterable $gameObjects
    ){
    }
}