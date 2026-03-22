<?php

namespace App\Stream;

use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\UX\Turbo\Broadcaster\BroadcasterInterface;
use Twig\Environment;

readonly class Streamer
{

    public function __construct(
        private BroadcasterInterface $twigBroadcaster,
        private HubInterface $mercure,
        private Environment $twig,
    )
    {
    }

    public function send(StreamInterface $stream): void
    {
        if ($stream instanceof BroadcastStreamInterface) {
            $this->twigBroadcaster->broadcast(
                $stream->getObject(),
                $stream->getAction(),
                [
                    'template' => $stream->getTemplate(),
                    'topics' => $stream->getTopics(),
                ] + $stream->getOptions()
            );
        } else {
            $this->mercure->publish(new Update(
                $stream->getTopics(),
                $this->twig->render($stream->getTemplate(), $stream->getOptions()),
                true
            ));
        }
    }

}