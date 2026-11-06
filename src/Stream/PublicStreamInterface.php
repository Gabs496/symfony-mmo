<?php

namespace App\Stream;

/**
 * Marker for streams whose content may be received by any subscriber of the topic
 * (published as a public Mercure update instead of a private one).
 */
interface PublicStreamInterface extends StreamInterface
{
}
