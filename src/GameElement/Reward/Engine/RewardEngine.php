<?php

namespace App\GameElement\Reward\Engine;

use App\GameElement\Reward\RewardApply;
use Symfony\Component\Messenger\MessageBusInterface;

class RewardEngine
{
    public function __construct(
        protected MessageBusInterface $messageBus,
    )
    {
    }

    public function apply(RewardApply $rewardApply): void
    {
        $rewardApply->clear();
        $this->messageBus->dispatch($rewardApply);
    }
}