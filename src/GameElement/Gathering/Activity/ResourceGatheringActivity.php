<?php

namespace App\GameElement\Gathering\Activity;

use App\GameElement\Activity\AbstractActivity;
use PennyPHP\Core\GameObject\GameObjectInterface;
use App\GameElement\Gathering\Component\ResourceComponent;

class ResourceGatheringActivity extends AbstractActivity
{
    protected ResourceComponent $gathering;
    protected string $resourceToken;

    public function __construct(
        GameObjectInterface $subject,
        protected ?GameObjectInterface  $resource,
    )
    {
        parent::__construct($subject);
        $this->resourceToken = $resource->getId();
        $this->gathering = $resource->getComponent(ResourceComponent::class);
    }

    public function getGathering(): ResourceComponent
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