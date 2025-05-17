<?php

namespace App\GameElement\ItemEquiment\Component;

use App\GameElement\Core\GameComponent\AbstractGameComponent;

class ItemConditionComponent extends AbstractGameComponent
{
    public function __construct(
        protected float $maxCondition,
        protected ?float $currentCondition = null,
    )
    {
        parent::__construct();
        $this->currentCondition = $currentCondition ?? $maxCondition;
    }

    public function getMaxCondition(): float
    {
        return $this->maxCondition;
    }

    public function setMaxCondition(float $maxCondition): void
    {
        $this->maxCondition = $maxCondition;
    }

    public function getCurrentCondition(): ?float
    {
        return $this->currentCondition;
    }

    public function setCurrentCondition(?float $currentCondition): void
    {
        $this->currentCondition = $currentCondition;
    }
}