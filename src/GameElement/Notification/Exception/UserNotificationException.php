<?php

namespace App\GameElement\Notification\Exception;

use RuntimeException;
use Symfony\Component\Messenger\Exception\UnrecoverableExceptionInterface;
use Throwable;

class UserNotificationException extends RuntimeException implements UnrecoverableExceptionInterface
{
    public function __construct(
        protected string $userId,
        string $message,
        Throwable $previous = null,
    )
    {
        parent::__construct(message: $message, previous: $previous);
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getMessages(): string
    {
        return $this->message;
    }
}