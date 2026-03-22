<?php

namespace App\GameElement\Notification\Engine;

use App\Entity\Data\Player;
use App\Stream\PlayerNotificationStream;
use App\Stream\Streamer;

readonly class NotificationEngine
{
    public function __construct(
        private Streamer $streamer,
    )
    {
    }

    public function success(Player $recipe, string $message): void
    {
        $this->streamer->send(new PlayerNotificationStream($message, "success", $recipe));
    }

    public function danger(Player $recipe, string $message): void
    {
        $this->streamer->send(new PlayerNotificationStream($message, "danger", $recipe));
    }
}