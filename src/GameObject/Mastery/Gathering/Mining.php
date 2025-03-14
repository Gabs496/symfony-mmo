<?php

namespace App\GameObject\Mastery\Gathering;

use App\GameElement\Mastery\MasteryType;

readonly class Mining extends MasteryType
{
    public function __construct()
    {
        parent::__construct('MINING');
    }

    public static function getName(): string
    {
        return 'Mining';
    }
}