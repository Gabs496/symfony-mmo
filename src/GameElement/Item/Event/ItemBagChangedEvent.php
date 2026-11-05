<?php

namespace App\GameElement\Item\Event;

use PennyPhp\ServerToClientEvent\Event\ClientEventInterface;

readonly class ItemBagChangedEvent implements ClientEventInterface
{
    public function __construct(
        private string $bagId,
    )
    {
    }

    public function getBagId(): string
    {
        return $this->bagId;
    }

    public function getClientEventName(): string
    {
        return 'item_bag_changed_' . $this->bagId;
    }
}
