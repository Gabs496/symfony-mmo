<?php

namespace App\GameElement\Reward\Engine;

use App\GameElement\Drop\Component\Drop;
use App\GameElement\Drop\Engine\DropEngine;
use App\GameElement\Reward\RewardApply;
use Symfony\Component\Messenger\MessageBusInterface;

class RewardEngine
{
    public function __construct(
        protected MessageBusInterface $messageBus,
        protected DropEngine $dropEngine,
    )
    {
    }

    public function apply(RewardApply $rewardApply): void
    {
        $reward = $rewardApply->getReward();
        foreach ($reward->getAttributes() as $attribute) {
            if($attribute instanceof Drop) {
                if (!$this->dropEngine->isLucky($attribute)) {
                    return;
                }
            }
        }

        $rewardApply->clear();
        $this->messageBus->dispatch($rewardApply);
    }
}