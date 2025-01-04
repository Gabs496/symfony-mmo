<?php

namespace App\GameElement\Gathering\Engine;

use App\Core\GameObject\AbstractGameObjectCollection;
use App\GameElement\Gathering\AbstractResource;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

/** @extends AbstractGameObjectCollection<AbstractResource> */
readonly class ResourceCollection extends AbstractGameObjectCollection
{
    public function __construct(
        #[AutowireIterator('game.resource')]
        protected iterable $gameObjects
    ){
    }
}