<?php

namespace App\Stream;

interface StreamInterface
{

    /** @return string[] */
    public function getTopics(): array;
    public function getTemplate(): string;

    public function getOptions(): array;

}