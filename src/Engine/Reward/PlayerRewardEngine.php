<?php

namespace App\Engine\Reward;

use App\GameElement\Reward\RewardInterface;
use App\GameElement\Reward\RewardPlayer;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Throwable;

readonly class PlayerRewardEngine
{
    public function __construct(
        private MessageBusInterface $messageBus,
    )
    {
    }

    /**
     * @param string $playerId
     * @param RewardInterface[] $rewards
     * @throws Throwable
     */
    public function reward(string $playerId, array $rewards): void
    {
        foreach ($rewards as $reward) {
            try {
                $this->messageBus->dispatch(new RewardPlayer($playerId, $reward));
            } catch (HandlerFailedException $exception) {
                throw $exception->getPrevious();
            }
        }
    }
}