<?php

namespace App\GameElement\Gathering\Activity;

use App\GameElement\Activity\Activity;
use App\GameElement\Activity\ActivityInterface;
use App\GameElement\Activity\ActivityWithRewardInterface;
use App\GameElement\Gathering\AbstractResource;
use App\GameElement\Item\Reward\ItemReward;
use App\GameElement\Mastery\MasteryReward;

#[Activity(id: 'RESOURCE_GATHERING')]
readonly class ResourceGatheringActivity implements ActivityInterface, ActivityWithRewardInterface
{
    public function __construct(
        private AbstractResource $resource,
    )
    {
    }

    public function getResource(): AbstractResource
    {
        return $this->resource;
    }

    public function getRewards(): iterable
    {
        yield new ItemReward($this->resource->getRewardItem(), 1);
        yield new MasteryReward($this->resource->getInvolvedMastery(), 0.01);
    }
}