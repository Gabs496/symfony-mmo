<?php

namespace App\Engine\Reward;

use App\GameElement\Reward\RewardPlayer;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;

readonly class PlayerRewardEngine
{
    public function __construct(
        private MessageBusInterface $messageBus
    )
    {
    }

    /**
     * @throws ExceptionInterface
     */
    public function reward(string $playerId, array $rewards): void
    {
        foreach ($rewards as $reward) {
            $this->messageBus->dispatch(new RewardPlayer($playerId, $reward));
        }
    }
}