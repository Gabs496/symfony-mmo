<?php

namespace App\GameElement\MapResource;

use App\GameElement\Gathering\AbstractResource;

class AbstractMapSpawnInstance
{
    public function __construct(
        protected AbstractResource $resource,
    )
    {
    }

    public function getResource(): AbstractResource
    {
        return $this->resource;
    }
}