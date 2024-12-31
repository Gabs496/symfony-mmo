<?php

namespace App\GameElement\Action;

use App\GameElement\GameElementInterface;
use Attribute;

#[Attribute]
readonly class ActionAvailable implements GameElementInterface
{
    public const string AS_SUBJECT = 'AS_SUBJECT';
    public const string AS_DIRECT_OBJECT = 'AS_SUBJECT';
    public function __construct(
        protected string $id,
        protected string $as,
    )
    {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getAs(): string
    {
        return $this->as;
    }

    public function isAsSubject(): bool
    {
        return $this->as === self::AS_SUBJECT;
    }

    public function isAsDirectObject(): bool
    {
        return $this->as === self::AS_DIRECT_OBJECT;
    }
}