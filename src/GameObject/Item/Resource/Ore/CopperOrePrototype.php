<?php

namespace App\GameObject\Item\Resource\Ore;

use App\Engine\Reward\MasteryReward;
use App\Entity\Core\GameObject;
use App\GameElement\Gathering\Component\GatheringComponent;
use App\GameObject\Item\AbstractItemResourcePrototype;
use App\GameObject\Mastery\Gathering\Mining;

class CopperOrePrototype extends AbstractItemResourcePrototype
{
    public const string ID = 'RESOURCE_ORE_COPPER';
    public function make(
        array $components = [],
        string $name = 'Coppper Ore',
        string $description = 'A piece of copper ore.',
        float $weight = 0.2,
    ): GameObject
    {
        return parent::make(
            components: $components,
            name: $name,
            description: $description,
            weight: $weight,
        );
    }

    public function asGatherableComponents(): array
    {
        return [
            new GatheringComponent(
                difficulty: 0.5,
                involvedMastery: Mining::getId(),
                gatheringTime: 1.5,
                rewards: [
                    new MasteryReward(Mining::getId(), 0.01),
                ]
            )
        ];
    }

    public static function getId(): string
    {
        return self::ID;
    }
}