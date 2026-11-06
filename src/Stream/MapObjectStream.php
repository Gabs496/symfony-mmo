<?php

namespace App\Stream;

use PennyPHP\Core\GameObjectInterface;

/**
 * Turbo Stream for a single game object shown in a map field.
 * Action is one of the blocks of streams/map_object.stream.html.twig: "append", "update", "remove".
 */
readonly class MapObjectStream implements BroadcastStreamInterface, PublicStreamInterface
{
    public const string APPEND = 'append';
    public const string UPDATE = 'update';
    public const string REMOVE = 'remove';

    public function __construct(
        private string              $mapId,
        private GameObjectInterface $gameObject,
        private string              $action,
    )
    {
    }

    public static function getTopic(string $mapId): string
    {
        return 'map_field_' . $mapId;
    }

    public function getTopics(): array
    {
        return [self::getTopic($this->mapId)];
    }

    public function getTemplate(): string
    {
        return 'streams/map_object.stream.html.twig';
    }

    public function getOptions(): array
    {
        return [
            'entity' => $this->gameObject,
            'id' => $this->gameObject->getId(),
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
