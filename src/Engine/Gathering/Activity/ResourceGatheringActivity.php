<?php

namespace App\Engine\Gathering\Activity;

use App\Engine\Player\Reward\MasteryReward;

class ResourceGatheringActivity extends \App\GameElement\Gathering\Activity\ResourceGatheringActivity
{
    public function getRewards(): iterable
    {
        yield new MasteryReward($this->resource->getInvolvedMastery(), 0.01);
        foreach (parent::getRewards() as $reward) {
            yield $reward;
        }
    }
}