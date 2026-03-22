<?php

namespace App\GameElement\Notification\Engine;

use App\Entity\Data\Player;
use App\Repository\Data\PlayerCharacterRepository;
use App\Stream\PlayerNotificationStream;
use App\Stream\Streamer;

readonly class NotificationEngine
{
    public function __construct(
        private Streamer $streamer,
        private PlayerCharacterRepository $playerCharacterRepository,
    )
    {
    }

    public function success(string|Player $recipe, string $message): void
    {
        if (is_string($recipe)) {
            $recipe = $this->playerCharacterRepository->find($recipe);
        }
        $this->streamer->send(new PlayerNotificationStream($message, "success", $recipe));
    }

    public function danger(string $recipe, string $message): void
    {
        if (is_string($recipe)) {
            $recipe = $this->playerCharacterRepository->find($recipe);
        }
        $this->streamer->send(new PlayerNotificationStream($message, "danger", $recipe));
    }
}