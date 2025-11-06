<?php

namespace App\GameObject\Mob;

use App\GameElement\Map\Render\MapRenderComponent;
use App\GameElement\Mob\AbstractMob;

abstract class AbstractBaseMob extends AbstractMob
{
    protected string $icon;

    public function __construct(
        string  $id,
        string  $name,
        float   $maxHealth,
        string  $description,
        array   $combatStats = [],
        array   $rewardOnDefeats = [],
        array   $components = [],
    )
    {
        parent::__construct(
            $id,
            $maxHealth,
            $combatStats,
            $rewardOnDefeats,
            components: array_merge([
                MapRenderComponent::class => new MapRenderComponent(
                    template: 'Render:MapRenderTemplate',
                    name: $name,
                    description: $description,
                    iconPath: '/mob/' . strtolower($id) . '.png',
                ),
            ], $components)
        );
    }
}