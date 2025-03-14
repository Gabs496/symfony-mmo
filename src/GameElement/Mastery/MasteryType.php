<?php

namespace App\GameElement\Mastery;

use App\GameElement\Core\GameObject\AbstractGameObject;

abstract readonly class MasteryType extends AbstractGameObject
{
    public static abstract function getName(): string;
    public function __toString(): string
    {
        return static::getId();
    }
}
