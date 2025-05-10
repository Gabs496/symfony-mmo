<?php

namespace App\GameObject\Item\Resource\Ore;

use App\GameObject\Item\AbstractItemResourcePrototype;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('game.object')]
readonly class CopperOrePrototype extends AbstractItemResourcePrototype
{
    public function __construct()
    {
        parent::__construct(
            id: 'RESOURCE_ORE_COPPER',
            name: 'Coppper Ore',
            description: 'A piece of copper ore.',
            weight: 0.1,
        );
    }
}