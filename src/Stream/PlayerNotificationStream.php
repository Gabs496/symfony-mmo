<?php

namespace App\Stream;

use Symfony\Component\Uid\Uuid;

readonly class PlayerNotificationStream extends AbstractPlayerGuiStream
{

    public function __construct(
        private string $message,
        private string $action,
        string $player
    )
    {
        parent::__construct($player);
    }

    public function getAction(): ?string
    {
        return $this->action;
    }

    public function getTemplate(): string
    {
        return 'streams/player_notification.stream.html.twig';
    }

    public function getOptions(): array
    {
        return ['id' => Uuid::v7(), 'message' => $this->message];
    }
}