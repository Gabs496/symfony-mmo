<?php

namespace App\GameElement\Gathering\Event;

use PennyPHP\Core\GameObjectInterface;

readonly class ResourceGatheringEndedEvent
{
    public function __construct(
        private GameObjectInterface $subject,
        private GameObjectInterface $resource,
    ) {
    }

    public function getResource(): GameObjectInterface
    {
        return $this->resource;
    }

    public function getSubject(): GameObjectInterface
    {
        return $this->subject;
    }
}