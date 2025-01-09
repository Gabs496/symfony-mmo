<?php

namespace App\GameElement\Item\Exception;

use RuntimeException;

class MaxBagSizeReachedException extends RuntimeException
{
    protected $message = 'Max size reached';
}