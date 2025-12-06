<?php

namespace App\GameObject\Item\Resource\Log;

use App\Engine\Reward\MasteryReward;
use App\Entity\Core\GameObject;
use App\GameElement\Gathering\Component\GatheringComponent;
use App\GameObject\Item\AbstractItemResourcePrototype;
use App\GameObject\Mastery\Gathering\Woodcutting;

class ChestnutLogPrototype extends AbstractItemResourcePrototype
{
    public const string ID = 'RESOURCE_LOG_CHESTNUT';
    public function make(
        array $components = [],
        string $name = 'Chestnut Log',
        string $description = 'A log from a chestnut tree.',
        float $weight = 0.1,
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
                involvedMastery: Woodcutting::getId(),
                gatheringTime: 1.5,
                rewards: [
                    new MasteryReward(Woodcutting::getId(), 0.01)
                ],
            )
        ];
    }

    public static function getId(): string
    {
        return self::ID;
    }
}