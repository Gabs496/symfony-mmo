<?php

namespace App\GameElement\Gathering\GameObject;

use App\GameElement\Core\GameObject\AbstractGameObjectPrototype;
use App\GameElement\Gathering\Component\Gathering;
use App\GameElement\Render\Component\Render;
use App\GameElement\Reward\RewardInterface;

abstract class AbstractResource extends AbstractGameObjectPrototype
{
    public function __construct(
        string          $id,
        string          $name,
        float           $difficulty,
        string          $involvedMastery,
        /** @param RewardInterface[] $rewards */
        protected array $rewards,
        float           $gatheringTime,
        array           $components = [],
    )
    {
        parent::__construct($id, array_merge(
            $components,
            [
                new Render(
                    name: $name,
                    iconPath: '/resource_gathering/' . strtolower($id) . '.png',
                    template: 'Gathering:ResourceRender',
                ),
                new Gathering(
                    difficulty: $difficulty,
                    involvedMastery: $involvedMastery,
                    gatheringTime: $gatheringTime,

                )
            ]
        ));
    }

    public function getRewards(): array
    {
        return $this->rewards;
    }
}