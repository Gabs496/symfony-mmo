<?php

namespace PennyPhp\ServerToClientEvent\Event;

interface ClientEventInterface
{
    public function getClientEventName(): string;
}
