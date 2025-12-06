<?php

namespace App\GameElement\Notification\Engine;

use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Uid\Uuid;
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
        $this->hub->publish(new Update('player_gui_' . $recipeId, $this->twig->load('notification/notification.stream.html.twig')->renderBlock('success', ['message' => $message, 'id' => Uuid::v7()]), true));
    }

    public function danger(string $recipeId, string $message): void
    {
        $this->hub->publish(new Update('player_gui_' . $recipeId, $this->twig->load('notification/notification.stream.html.twig')->renderBlock('danger',['message' => $message, 'id' => Uuid::v7()]), true));
    }
}