<?php

namespace App\GameElement\ItemBag\Exception;

use RuntimeException;

class MaxSizeReachedException extends RuntimeException
{
    protected $message = 'Max size reached';
}