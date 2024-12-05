<?php

namespace App\GameTask\Message;

readonly class RewardItem implements RewardPlayerCharacterInterface
{
    public function __construct(
        private string $playerCharacterId,
        private string  $itemId,
        private int $quantity = 1
    )
    {
    }

    public function getPlayerCharacterId(): string
    {
        return $this->playerCharacterId;
    }

    public function getItemId(): string
    {
        return $this->itemId;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }
}