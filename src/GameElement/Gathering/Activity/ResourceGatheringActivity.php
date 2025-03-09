<?php

namespace App\GameElement\Gathering\Activity;

use App\Entity\Data\MapAvailableActivity;
use App\GameElement\Activity\Activity;
use App\GameElement\Activity\ActivityInterface;
use App\GameElement\Activity\ActivityWithRewardInterface;
use App\GameElement\Gathering\AbstractResource;
use App\GameElement\Item\Reward\ItemReward;
use App\GameElement\Mastery\MasteryReward;

#[Activity(id: 'RESOURCE_GATHERING')]
readonly class ResourceGatheringActivity implements ActivityInterface, ActivityWithRewardInterface
{
    private AbstractResource $resource;
    public function __construct(
        private MapAvailableActivity $mapAvailableActivity,
    )
    {
        $this->resource = $this->mapAvailableActivity->getMapResource()->getResource();
    }

    public function getResource(): AbstractResource
    {
        return $this->resource;
    }

    public function getMapAvailableActivity(): MapAvailableActivity
    {
        return $this->mapAvailableActivity;
    }

    public function getRewards(): iterable
    {
        yield new ItemReward($this->resource->getRewardItem(), 1);
        yield new MasteryReward($this->resource->getInvolvedMastery(), 0.01);
    }
}