<?php

namespace App\Entity;

use App\Interface\ActivityEventInterface;

class ActivityStep
{
    /**
     * Duration in seconds
     */
    private float $duration;
    private bool $isCompleted = false;

    private array $onFinish = [];

    public function __construct(float $duration)
    {
        $this->duration = $duration;
    }

    public function getDuration(): float
    {
        return $this->duration;
    }

    public function isCompleted(): bool
    {
        return $this->isCompleted;
    }

    public function setIsCompleted(bool $isCompleted): void
    {
        $this->isCompleted = $isCompleted;
    }

    public function getOnFinish(): array
    {
        return $this->onFinish;
    }

    public function setOnFinish(array $onFinish): void
    {
        $this->onFinish = $onFinish;
    }

    public function addOnFinish(ActivityEventInterface $event): static
    {
        $this->onFinish[] = $event;
        return $this;
    }
}