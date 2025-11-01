<?php

namespace App\GameElement\Gathering\Activity;

use App\Entity\Game\GameObject;
use App\GameElement\Activity\AbstractActivity;
use App\GameElement\Core\Token\TokenizableInterface;
use App\GameElement\Gathering\Component\Gathering;

class ResourceGatheringActivity extends AbstractActivity
{
    protected Gathering $gathering;
    protected string $resourceToken;

    public function __construct(
        TokenizableInterface $subject,
        protected ?GameObject  $resource,
    )
    {
        parent::__construct($subject);
        $this->resourceToken = $resource->getId();
        $this->gathering = $resource->getComponent(Gathering::class);
    }

    public function getGathering(): Gathering
    {
        return $this->gathering;
    }

    public function getResource(): GameObject
    {
        return $this->resource;
    }

    public function setResource(GameObject $resource): void
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