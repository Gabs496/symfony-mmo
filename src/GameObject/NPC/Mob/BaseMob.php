<?php

namespace App\GameObject\NPC\Mob;

use App\Engine\Combat\RewardOnDefeatInterface;

abstract class BaseMob extends \App\GameElement\NPC\BaseMob implements RewardOnDefeatInterface
{
    protected string $icon;

    public function __construct(
        string $name,
        float $maxHealth,
        string $description,
        ?string $icon = null
    )
    {
        parent::__construct($name, $maxHealth, $description);
        $this->icon = $icon ?? '/mob/' . strtolower(get_class($this)) . '.png';
    }
}