<?php

namespace App\GameElement\Map\Engine;

use App\Core\GameObject\AbstractGameObjectCollection;
use App\Core\GameObject\GameObjectCollection;
use App\GameElement\Map\AbstractMap;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

#[AutoconfigureTag('game.object_collection')]
#[GameObjectCollection(AbstractMap::class)]
readonly class MapCollection extends AbstractGameObjectCollection
{
    public function __construct(
        #[AutowireIterator('game.map')]
        protected iterable $gameObjects
    )
    {
    }
}