<?php

namespace App\GameElement\Gathering\Event;

use PennyPHP\Core\GameObjectInterface;

readonly class ResourceGatheredEvent
{
    public function __construct(
        private GameObjectInterface $subject,
        private GameObjectInterface $resource,
    ) {
    }

    public function getSubject(): GameObjectInterface
    {
        return $this->subject;
    }

    public function getResource(): GameObjectInterface
    {
        return $this->resource;
    }
}