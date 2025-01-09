<?php

namespace App\GameElement\Notification\Engine;

use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class NotificationEngine
{
    public function __construct(
        protected HubInterface $hub,
        protected Environment $twig,
    )
    {
    }

    /**
     * @throws LoaderError
     * @throws SyntaxError|RuntimeError
     */
    public function success(string $recipeId, string $message): void
    {
        $this->hub->publish(new Update('notification_' . $recipeId, $this->twig->load('notification/notification.stream.html.twig')->renderBlock('success', ['message' => $message])));
    }

    public function danger(string $recipeId, string $message): void
    {
        $this->hub->publish(new Update('notification_' . $recipeId, $this->twig->load('notification/notification.stream.html.twig')->renderBlock('danger',['message' => $message])));
    }
}