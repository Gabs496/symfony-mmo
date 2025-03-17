<?php

namespace App\GameElement\Gathering\Activity;

use App\GameElement\Activity\Activity;
use App\GameElement\Activity\AbstractActivity;
use App\GameElement\Gathering\AbstractResource;
use App\GameElement\Item\Reward\ItemReward;
use App\GameElement\MapResource\AbstractMapResourceSpawnInstance;
use App\GameElement\Mastery\MasteryReward;

#[Activity(id: 'RESOURCE_GATHERING')]
class ResourceGatheringActivity extends AbstractActivity
{
    private AbstractResource $resource;
    public function __construct(
        private readonly AbstractMapResourceSpawnInstance $spawnInstance,
    )
    {
        $this->resource = $this->spawnInstance->getResource();
    }

    public function getResource(): AbstractResource
    {
        return $this->resource;
    }

    public function getMapSpawnInstance(): AbstractMapResourceSpawnInstance
    {
        return $this->spawnInstance;
    }

    public function getRewards(): iterable
    {
        yield new ItemReward($this->resource->getRewardItem(), 1);
        yield new MasteryReward($this->resource->getInvolvedMastery(), 0.01);
    }
}