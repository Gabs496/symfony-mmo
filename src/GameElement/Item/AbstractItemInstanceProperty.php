<?php

namespace App\GameElement\Item;

abstract class AbstractItemInstanceProperty
{
    public function __construct(
        protected mixed $value,
    )
    {
    }

    public function setValue(mixed $value): void
    {
        $this->value = $value;
    }

    public function getValue(): mixed
    {
        return $this->value;
    }
}