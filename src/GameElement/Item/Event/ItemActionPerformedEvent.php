<?php

namespace App\GameElement\Item\Event;

use App\GameElement\Item\AbstractItem;
use App\GameElement\Item\AvailableAction\AbstractAvailableAction;

class ItemActionPerformedEvent
{
    public function __construct(
        protected object $performer,
        protected AbstractAvailableAction $action,
        protected AbstractItem $item,
        protected array $targets = [],
    )
    {

    }

    public function getPerformer(): object
    {
        return $this->performer;
    }

    public function getAction(): AbstractAvailableAction
    {
        return $this->action;
    }

    public function getItem(): AbstractItem
    {
        return $this->item;
    }

    public function getTargets(): array
    {
        return $this->targets;
    }
}