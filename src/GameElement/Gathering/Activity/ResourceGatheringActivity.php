<?php

namespace App\GameElement\Gathering\Activity;

use App\GameElement\Activity\AbstractActivity;
use App\GameElement\Activity\ActivitySubjectTokenInterface;
use App\GameElement\Activity\Activity;
use App\GameElement\Gathering\AbstractResource;
use App\GameElement\Gathering\Reward\ItemReward;

#[Activity(id: 'RESOURCE_GATHERING')]
class ResourceGatheringActivity extends AbstractActivity
{
    public function __construct(
        ActivitySubjectTokenInterface       $subject,
        protected readonly AbstractResource $resource,
        protected readonly mixed            $resourceInstanceId,
    )
    {
        parent::__construct($subject);
        $this->duration = $this->resource->getGatheringTime();
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
    }
}