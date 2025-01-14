<?php

namespace App\GameElement\Item\Engine;

use App\GameElement\Core\GameObject\AbstractGameObjectCollection;
use App\GameElement\Core\GameObject\GameObjectCollection;
use App\GameElement\Item\AbstractItem;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

#[AutoconfigureTag('game.object_collection')]
#[GameObjectCollection(AbstractItem::class)]
readonly class ItemCollection extends AbstractGameObjectCollection
{
    public function __construct(
        /** @var AbstractItem[] $gameObjects */
        #[AutowireIterator('game.item')]
        protected iterable $gameObjects,
    ) {
    }
}