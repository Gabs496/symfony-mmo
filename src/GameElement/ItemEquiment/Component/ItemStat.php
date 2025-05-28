<?php

namespace App\GameElement\ItemEquiment\Component;

class ItemStat
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