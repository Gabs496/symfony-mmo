<?php

namespace App\GameElement\Map\Event;


use PennyPhp\ServerToClientEvent\Event\ClientEventInterface;

readonly class MapFieldChangedEvent implements ClientEventInterface
{

    public function __construct(
        private string $mapId,
    )
    {
    }

    public function getMapId(): string
    {
        return $this->mapId;
    }

    public function getClientEventName(): string
    {
        return 'map_field_changed_' . $this->mapId;
    }
}
