<?php

namespace App\GameObject\Mastery\Gathering;

use App\GameElement\Mastery\MasteryType;

class Woodcutting extends MasteryType
{
    public function getId(): string
    {
        return 'WOODCUTTING';
    }

    public static function getName(): string
    {
        return 'Woodcutting';
    }
}