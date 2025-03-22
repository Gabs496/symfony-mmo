<?php

namespace App\GameElement\Item\Event;

use App\Engine\Item\Action\AbstractAvailableAction;
use App\GameElement\Item\ItemInstanceInterface;

class ItemActionPerformedEvent
{
    public function __construct(
        protected object                  $performer,
        protected AbstractAvailableAction $action,
        protected ItemInstanceInterface   $itemInstance,
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

    public function getItemInstance(): ItemInstanceInterface
    {
        return $this->itemInstance;
    }

    public function getTargets(): array
    {
        return $this->targets;
    }
}