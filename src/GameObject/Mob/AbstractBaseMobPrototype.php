<?php

namespace App\GameObject\Mob;

use App\Entity\Core\GameObject;
use App\GameElement\Map\Render\MapRenderTemplateComponent;
use App\GameElement\Mob\AbstractMobPrototype;

abstract class AbstractBaseMobPrototype extends AbstractMobPrototype
{
    public function make(
        array  $components = [],
        string $name = 'Mob',
        string $description = '',
        string $iconPath = '',
        float  $maxHealth = 0.0,
        array  $combatStats = [],
    ): GameObject
    {
        $iconPath = $iconPath ?: '/mob/' . strtolower($this->getId()) . '.png';
        $gameObject = parent::make(
            components: $components,
            name: $name,
            description: $description,
            iconPath: $iconPath,
            maxHealth: $maxHealth,
            combatStats: $combatStats,
        );
        $gameObject->setComponent(new MapRenderTemplateComponent(
            template: 'Render:MapRenderTemplate',
        ));
        return $gameObject;
    }
}