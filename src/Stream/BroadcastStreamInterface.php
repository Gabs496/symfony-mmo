<?php

namespace App\Stream;

interface BroadcastStreamInterface extends StreamInterface
{
    public function getObject(): ?object;
}