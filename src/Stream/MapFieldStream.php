<?php

namespace App\Stream;

use PennyPHP\Core\GameObjectInterface;

readonly class MapFieldStream implements StreamInterface, BroadcastStreamInterface
{
    public function __construct(
        private string $mapId,
        private GameObjectInterface $gameObject,
        private string $action,
    )
    {
    }

    public function getTopics(): array
    {
        return ['map_field_' . $this->mapId];
    }

    public function getTemplate(): string
    {
        return 'streams/map_field.stream.html.twig';
    }

    public function getOptions(): array
    {
        return [
            'entity' => $this->gameObject,
            'id' => $this->mapId,
        ];
    }

    public function getObject(): ?object
    {
        return $this->gameObject;
    }

    public function getAction(): ?string
    {
        return $this->action;
    }
}