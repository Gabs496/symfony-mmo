<?php

namespace App\GameElement\Item\Engine;

use App\GameElement\Item\AbstractItemPrototype;
use InvalidArgumentException;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

class ItemPrototypeEngine
{
    public function __construct(
        /** @var AbstractItemPrototype[] */
        #[AutowireIterator('item.prototype')]
        protected iterable $itemPrototypes,
    )
    {

    }

    public function get(string $itemPrototypeId)
    {
        foreach ($this->itemPrototypes as $itemPrototype) {
            if ($itemPrototype->getId() === $itemPrototypeId) {
                return $itemPrototype;
            }
        }
        throw new InvalidArgumentException('Item prototype with id ' . $itemPrototypeId . ' not found');
    }
}