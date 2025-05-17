<?php

namespace App\GameElement\Health;

use App\GameElement\Health\Component\Health;

interface HasHealthComponentInterface
{
    public function getHealthComponent(): Health;
}