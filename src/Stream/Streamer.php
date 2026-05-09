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
        $parameters = $stream->getOptions();
        $loadedTemplate = $this->twig->load($stream->getTemplate());
        if ($stream instanceof BroadcastStreamInterface) {
            $parameters['object'] = $stream->getObject();
        }
        if ($stream->getAction()) {
            $template = $loadedTemplate->renderBlock($stream->getAction(), $parameters);
        } else {
            $template = $loadedTemplate->render($parameters);
        }
        $this->mercure->publish(new Update(
            $stream->getTopics(),
            $template,
            true
        ));
    }

}