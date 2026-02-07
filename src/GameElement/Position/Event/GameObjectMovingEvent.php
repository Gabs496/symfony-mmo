<?php

namespace App\GameElement\Position\Event;

use PennyPHP\Core\GameObject\Entity\GameObject;
use App\GameElement\Position\Component\PositionComponent;

readonly class GameObjectMovingEvent
{
    public function __construct(
        private GameObject        $gameObject,
        private PositionComponent $positionComponent,
    )
    {

    }

    public function getGameObject(): GameObject
    {
        return $this->gameObject;
    }

    public function getPositionComponent(): PositionComponent
    {
        return $this->positionComponent;
    }
}