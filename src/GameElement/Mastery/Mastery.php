<?php

namespace App\GameElement\Mastery;

class Mastery
{
    public function __construct(
        protected readonly string $type,
        protected float $experience
    )
    {
    }
    public function getType(): string
    {
        return $this->type;
    }

    public function getExperience(): float
    {
        return $this->experience;
    }

    public function increaseExperience(float $value): static
    {
        $this->experience = (float)bcadd($this->experience, $value, 2);
        return $this;
    }
}