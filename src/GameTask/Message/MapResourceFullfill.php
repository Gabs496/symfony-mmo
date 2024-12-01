<?php

namespace App\GameTask\Message;

readonly class MapResourceFullfill
{
    public function __construct(
        private string $mapResourceId
    ){

    }

    public function getMapResourceId(): string
    {
        return $this->mapResourceId;
    }

}