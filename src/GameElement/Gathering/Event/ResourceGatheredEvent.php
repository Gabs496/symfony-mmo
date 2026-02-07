<?php

namespace App\GameElement\Gathering\Event;

use PennyPHP\Core\GameObject\GameObjectInterface;

readonly class ResourceGatheredEvent
{
    public function __construct(
        private GameObjectInterface $subject,
        private GameObjectInterface $item,
    ) {
    }

    public function getSubject(): GameObjectInterface
    {
        return $this->subject;
    }

    public function getItem(): GameObjectInterface
    {
        return $this->item;
    }
}