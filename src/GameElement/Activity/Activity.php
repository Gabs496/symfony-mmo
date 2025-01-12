<?php

namespace App\GameElement\Activity;

use Attribute;

#[Attribute]
class Activity
{
    public function __construct(
        protected string $id
    )
    {

    }

    public function getId(): string
    {
        return $this->id;
    }

}