<?php

namespace App\GameObject\Mastery\Gathering;

use App\GameElement\Mastery\MasteryType;

readonly class Woodcutting extends MasteryType
{
    public function __construct()
    {
        parent::__construct('WOODCUTTING');
    }

    public static function getName(): string
    {
        return 'Woodcutting';
    }
}