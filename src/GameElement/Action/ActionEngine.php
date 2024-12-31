<?php

namespace App\GameElement\Action;

use App\GameElement\GameElementInterface;
use Attribute;

#[Attribute]
class ActionEngine implements GameElementInterface
{
    public function __construct(
        protected string $id,
    )
    {

    }

    public function getId(): string
    {
        return $this->id;
    }
}