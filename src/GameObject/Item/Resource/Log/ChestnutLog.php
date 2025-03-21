<?php

namespace App\GameObject\Item\Resource\Log;

use App\GameObject\Item\Resource\AbstractBaseResource;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('game.object')]
readonly class ChestnutLog extends AbstractBaseResource
{
    public const string ID = 'RESOURCE_LOG_CHESTNUT';
    public function __construct()
    {
        parent::__construct(
            id: self::ID,
            name: 'Chestnut Log',
            description: 'A log from a chestnut tree.',
            weight: 0.1,
        );
    }
}