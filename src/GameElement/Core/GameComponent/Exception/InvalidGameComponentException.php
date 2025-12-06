<?php

namespace App\GameElement\Core\GameComponent\Exception;

use Exception;

class InvalidGameComponentException extends Exception
{
    public $message = 'The provided component is not a valid GameComponentInterface instance.';
}