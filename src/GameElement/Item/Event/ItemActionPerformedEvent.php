<?php

namespace App\GameElement\Item\Event;

use App\Engine\Item\Action\AbstractAvailableAction;
use App\GameElement\Core\GameObject\GameObjectInterface;

class ItemActionPerformedEvent
{
    public function __construct(
        protected object                  $performer,
        protected AbstractAvailableAction $action,
        protected GameObjectInterface     $item,
        protected array                   $targets = [],
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

    public function getItem(): GameObjectInterface
    {
        return $this->item;
    }

    public function getTargets(): array
    {
        return $this->targets;
    }
}