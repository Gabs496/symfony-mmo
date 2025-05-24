<?php

namespace App\GameElement\Drop\Engine;

use App\GameElement\Drop\Component\Drop;

class DropEngine
{
    public function isLucky(Drop $drop): bool
    {
        $result = random_int(0, 1000000) / 1000000;
        return $result < $drop->getRate();
    }
}