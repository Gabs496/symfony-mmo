<?php

namespace App\GameElement\Notification\EventListener;

use App\GameElement\Notification\Engine\NotificationEngine;
use App\GameElement\Notification\Exception\UserNotificationException;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\Messenger\Event\WorkerMessageFailedEvent;
use Symfony\Component\Messenger\Exception\HandlerFailedException;

#[AsEventListener(event: 'kernel.exception')]
#[AsEventListener(event: WorkerMessageFailedEvent::class, method: 'onMessageFailed')]
readonly class ExceptionListener
{
    public function __construct(private NotificationEngine $notificationEngine)
    {
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        if ($exception instanceof UserNotificationException) {
            $this->notificationEngine->danger($exception->getUserId(), $exception->getMessages());
            $event->setResponse(new Response());
        }
    }

    public function onMessageFailed(WorkerMessageFailedEvent $event): void
    {
        $exception = $event->getThrowable();
        if ($exception instanceof HandlerFailedException) {
            $exception = $exception->getPrevious();
        }

        if ($exception instanceof UserNotificationException) {
            $this->notificationEngine->danger($exception->getUserId(), $exception->getMessages());
        }
    }
}