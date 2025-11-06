<?php

namespace App\GameElement\Gathering\Activity;

use App\GameElement\Activity\AbstractActivity;
use App\GameElement\Core\GameObject\GameObjectInterface;
use App\GameElement\Gathering\Component\GatheringComponent;

class ResourceGatheringActivity extends AbstractActivity
{
    protected GatheringComponent $gathering;
    protected string $resourceToken;

    public function __construct(
        GameObjectInterface $subject,
        protected ?GameObjectInterface  $resource,
    )
    {
        parent::__construct($subject);
        $this->resourceToken = $resource->getId();
        $this->gathering = $resource->getComponent(GatheringComponent::class);
    }

    public function getGathering(): GatheringComponent
    {
        return $this->gathering;
    }

    public function getResource(): GameObjectInterface
    {
        return $this->resource;
    }

    public function setResource(GameObjectInterface $resource): void
    {
        $this->resource = $resource;
    }

    public function getResourceToken(): string
    {
        return $this->resourceToken;
    }

    public function clear(): void
    {
        parent::clear();
        $this->resource = null;
    }
}