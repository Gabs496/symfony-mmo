<?php

namespace App\GameElement\ItemBag;

use RuntimeException;

class MaxSizeReachedException extends RuntimeException
{
    protected $message = 'Max size reached';
}