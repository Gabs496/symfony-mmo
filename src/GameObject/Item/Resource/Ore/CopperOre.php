<?php

namespace App\GameObject\Item\Resource\Ore;

use App\GameElement\Item\AbstractItem;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('game.item')]
readonly class CopperOre extends AbstractItem
{
    public const string ID = 'RESOURCE_ORE_COPPER';
    public function __construct()
    {
        parent::__construct(
            id: self::ID,
            name: 'Coppper Ore',
            description: 'A piece of copper ore.',
            stackable: true,
            weight: 0.1,
        );
    }
}