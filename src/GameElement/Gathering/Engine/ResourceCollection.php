<?php

namespace App\GameElement\Gathering\Engine;

use App\GameElement\Core\GameObject\AbstractGameObjectCollection;
use App\GameElement\Core\GameObject\GameObjectCollection;
use App\GameElement\Gathering\AbstractResource;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

#[AutoconfigureTag('game.object_collection')]
#[GameObjectCollection(AbstractResource::class)]
/** @extends AbstractGameObjectCollection<AbstractResource> */
readonly class ResourceCollection extends AbstractGameObjectCollection
{
    public function __construct(
        #[AutowireIterator('game.resource')]
        protected iterable $gameObjects
    ){
    }
}