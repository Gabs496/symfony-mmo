<?php

namespace App\Engine;

use App\GameElement\Activity\ActivitySubjectInterface;
use App\GameElement\Combat\CombatOpponentInterface;
use App\GameElement\Core\GameObject\GameObjectInterface;
use App\GameElement\Reward\RewardRecipeInterface;

readonly class PlayerCharacterManager
    implements GameObjectInterface, ActivitySubjectInterface, RewardRecipeInterface, CombatOpponentInterface
{
    public function __construct(
        private string $id,
    )
    {
    }

    public function getId(): string
    {
        return $this->id;
    }
}