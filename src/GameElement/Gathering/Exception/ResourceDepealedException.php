<?php

namespace App\GameElement\Gathering\Exception;

use Exception;

class ResourceDepealedException extends Exception
{
    public function __construct(string $message = "Resource depealed", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}