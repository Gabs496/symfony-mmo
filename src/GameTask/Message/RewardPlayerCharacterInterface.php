<?php

namespace App\GameTask\Message;

interface RewardPlayerCharacterInterface extends RewardInterface
{
    public function getPlayerCharacterId(): string;
}