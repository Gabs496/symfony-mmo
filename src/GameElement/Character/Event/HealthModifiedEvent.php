<?php

namespace App\GameElement\Character\Event;

use App\GameElement\Character\Component\CharacterComponent;
use PennyPHP\Core\GameObject\GameObjectInterface;

class HealthModifiedEvent
{
    public function __construct(
        protected GameObjectInterface $object,
        protected CharacterComponent  $characterComponent,
    ) {
    }

    public function getObject(): GameObjectInterface
    {
        return $this->object;
    }

    public function getCharacterComponent(): CharacterComponent
    {
        return $this->characterComponent;
    }
}