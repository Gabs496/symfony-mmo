<?php

namespace App\Stream;

use App\Entity\Data\Player;

readonly class PlayerHealthStream extends AbstractPlayerGuiStream implements BroadcastStreamInterface
{

    public function __construct(
        private string $action,
        Player $player
    )
    {
        parent::__construct($player);
    }

    public function getObject(): ?object
    {
        return $this->getPlayer();
    }

    public function getAction(): ?string
    {
        return $this->action;
    }

    public function getTemplate(): string
    {
        return 'streams/player_health.stream.html.twig';
    }

    public function getOptions(): array
    {
        return ['player' => $this->getPlayer()];
    }
}