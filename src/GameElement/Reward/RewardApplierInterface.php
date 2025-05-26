<?php

namespace App\GameElement\Reward;

interface RewardApplierInterface
{
    public function supports(RewardApply $rewardApply): bool;
    public function apply(RewardApply $rewardApply): void;
}