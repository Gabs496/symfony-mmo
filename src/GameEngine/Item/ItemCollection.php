<?php

namespace App\GameEngine\Item;

use App\Core\GameObject\AbstractGameObjectCollection;
use App\Core\GameObject\GameObjectCollection;
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