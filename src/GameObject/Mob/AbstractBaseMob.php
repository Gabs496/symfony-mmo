<?php

namespace App\GameObject\Mob;

use App\GameElement\Mob\AbstractMob;

abstract readonly class AbstractBaseMob extends AbstractMob
{
    protected string $icon;

    public function __construct(
        string $id,
        string $name,
        float $maxHealth,
        string $description,
        ?string $icon = null,
        array $combatStats = [],
        array $rewardOnDefeats = [],
    )
    {
        parent::__construct($id, $name, $maxHealth, $description, $combatStats, $rewardOnDefeats);
        $this->icon = $icon ?? '/mob/' . strtolower(get_class($this)) . '.png';
    }
}