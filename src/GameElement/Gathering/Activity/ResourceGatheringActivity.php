<?php

namespace App\GameElement\Gathering\Activity;

use App\Engine\Player\Reward\ItemReward;
use App\Engine\Player\Reward\MasteryReward;
use App\GameElement\Activity\AbstractActivity;
use App\GameElement\Activity\Activity;
use App\GameElement\Gathering\AbstractResource;

#[Activity(id: 'RESOURCE_GATHERING')]
class ResourceGatheringActivity extends AbstractActivity
{
    public function __construct(
        private readonly AbstractResource $resource,
        private readonly mixed            $resourceInstanceId,
    )
    {
    }

    public function getResource(): AbstractResource
    {
        return $this->resource;
    }

    public function getResourceInstanceId(): mixed
    {
        return $this->resourceInstanceId;
    }

    public function getRewards(): iterable
    {
        yield new ItemReward($this->resource->getRewardItem(), 1);
        yield new MasteryReward($this->resource->getInvolvedMastery(), 0.01);
    }
}