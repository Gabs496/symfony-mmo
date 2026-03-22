<?php

namespace App\Stream;

use App\Entity\Activity\Activity;
use App\Entity\Data\Player;

readonly class PlayerActivityStream extends AbstractPlayerGuiStream implements BroadcastStreamInterface
{

    public function __construct(
        private Activity $activity,
        private string $action,
        Player $player
    )
    {
        parent::__construct($player);
    }

    public function getObject(): ?object
    {
        return $this->activity;
    }

    public function getAction(): ?string
    {
        return $this->action;
    }

    public function getTemplate(): string
    {
        return 'streams/PlayerActivity.stream.html.twig';
    }

    public function getOptions(): array
    {
        return ['activity' => $this->activity];
    }
}