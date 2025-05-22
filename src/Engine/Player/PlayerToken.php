<?php

namespace App\Engine\Player;

use App\Entity\Data\PlayerCharacter;
use App\GameElement\Activity\ActivitySubjectTokenInterface;
use App\GameElement\Combat\CombatOpponentTokenInterface;
use App\GameElement\Reward\RewardRecipe;

readonly class PlayerToken implements ActivitySubjectTokenInterface, RewardRecipe, CombatOpponentTokenInterface
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

    public function getCombatOpponentClass(): string
    {
        return PlayerCharacter::class;
    }
}
