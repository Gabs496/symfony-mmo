<?php

namespace App\GameElement\Item\Exception;

use RuntimeException;

class MaxSizeReachedException extends RuntimeException
{
    protected $message = 'Max size reached';
}