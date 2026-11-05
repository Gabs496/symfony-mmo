<?php

namespace PennyPhp\ServerToClientEvent\EventDispatcher;

use PennyPhp\ServerToClientEvent\Event\ClientEventInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Serializer\SerializerInterface;
use Webmozart\Assert\Assert;

readonly class ClientEventDispatcher implements EventDispatcherInterface
{

    public function __construct(
        private HubInterface $hub,
        private SerializerInterface $serializer,
    )
    {
    }

    public function dispatch(object $event): void
    {
        Assert::isInstanceOf($event, ClientEventInterface::class);
        $this->hub->publish(new Update('server:event', $this->serializer->serialize($event, 'json')));
    }
}
