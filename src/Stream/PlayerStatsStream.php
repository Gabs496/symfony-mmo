<?php

namespace App\Stream;

readonly class PlayerStatsStream extends AbstractPlayerGuiStream
{
    public function getTemplate(): string
    {
        return 'streams/player_stats.stream.html.twig';
    }

    public function getAction(): ?string
    {
        return null;
    }

    public function getOptions(): array
    {
        return ['playerCharacter' => $this->getPlayer()];
    }
}