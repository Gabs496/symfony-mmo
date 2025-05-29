<?php

namespace App\GameElement\Gathering\Activity;

use App\Entity\Game\MapObject;
use App\GameElement\Activity\AbstractActivity;
use App\GameElement\Activity\Activity;
use App\GameElement\Core\Token\TokenizableInterface;
use App\GameElement\Gathering\Component\Gathering;

#[Activity(id: 'RESOURCE_GATHERING')]
class ResourceGatheringActivity extends AbstractActivity
{
    protected Gathering $gathering;

    public function __construct(
        TokenizableInterface       $subject,
        protected readonly MapObject $resource,
    )
    {
        parent::__construct($subject);
        $this->gathering = $this->resource->getComponent(Gathering::class);
        $this->duration = $this->gathering->getGatheringTime();
    }

    public function getGathering(): Gathering
    {
        return $this->gathering;
    }

    public function getResource(): MapObject
    {
        return $this->resource;
    }
}