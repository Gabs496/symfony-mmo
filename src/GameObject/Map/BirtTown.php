<?php

namespace App\GameObject\Map;

readonly class BirtTown extends AbstractMap
{
    protected string $description;

    public function __construct()
    {
        parent::__construct('BIRT_TOWN', 'Birt Town', 0.0, 0.0);
        $this->description = 'A small town in the middle of nowhere.';
    }
}