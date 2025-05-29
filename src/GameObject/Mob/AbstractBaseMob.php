<?php

namespace App\GameObject\Mob;

use App\GameElement\Mob\AbstractMob;

abstract class AbstractBaseMob extends AbstractMob
{
    protected string $icon;

    public function __construct(
        string  $id,
        string  $name,
        float   $maxHealth,
        string  $description,
        ?string $iconPath = null,
        array   $combatStats = [],
        array   $rewardOnDefeats = [],
    )
    {
        parent::__construct($id, $name, $description, $maxHealth, $combatStats, $rewardOnDefeats, $iconPath);
    }
}