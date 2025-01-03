<?php

namespace App\Core\GameObject\Exception;

use RuntimeException;

class GameObjectCollectionNotFound extends RuntimeException
{
    protected $message = 'Game object collection not found for class %s';
}