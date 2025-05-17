<?php

namespace App\GameElement\ItemEquiment\Component;

use App\GameElement\Core\GameComponent\AbstractGameComponent;

class ItemStatComponent extends AbstractGameComponent
{
    public function __construct(
        protected array $stats,
    )
    {
    }

    public function getStats(): array
    {
        return $this->stats;
    }

    public function setStats(array $stats): void
    {
        $this->stats = $stats;
    }
}