<?php

namespace App\GameObject\Item\Resource\Log;

use App\GameElement\Item\AbstractItem;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('game.item')]
readonly class ChestnutLog extends AbstractItem
{
    public const string ID = 'RESOURCE_LOG_CHESTNUT';
    public function __construct()
    {
        parent::__construct(
            id: self::ID,
            name: 'Chestnut Log',
            description: 'A log from a chestnut tree.',
            stackable: true,
            weight: 0.1,
        );
    }
}