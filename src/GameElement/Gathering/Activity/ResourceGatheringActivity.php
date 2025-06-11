<?php

namespace App\GameElement\Gathering\Activity;

use App\Entity\Game\MapObject;
use App\GameElement\Activity\AbstractActivity;
use App\GameElement\Core\Token\TokenizableInterface;
use App\GameElement\Gathering\Component\Gathering;
use App\GameElement\Map\Token\MapObjectToken;

class ResourceGatheringActivity extends AbstractActivity
{
    protected Gathering $gathering;
    protected MapObjectToken $resourceToken;

    public function __construct(
        TokenizableInterface $subject,
        protected ?MapObject  $resource,
    )
    {
        parent::__construct($subject);
        $this->resourceToken = $resource->getToken();
        $this->gathering = $resource->getComponent(Gathering::class);
    }

    public function getGathering(): Gathering
    {
        return $this->gathering;
    }

    public function getResource(): MapObject
    {
        return $this->resource;
    }

    public function setResource(MapObject $resource): void
    {
        $this->resource = $resource;
    }

    public function getResourceToken(): MapObjectToken
    {
        return $this->resourceToken;
    }

    public function clear(): void
    {
        parent::clear();
        $this->resource = null;
    }
}