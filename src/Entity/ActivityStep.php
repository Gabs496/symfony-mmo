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

    private ?float $scheduledAt = null;

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

    public function getScheduledAt(): ?float
    {
        return $this->scheduledAt;
    }

    public function setScheduledAt(?float $scheduledAt): void
    {
        $this->scheduledAt = $scheduledAt;
    }

    public function getSecondsToFinish(): ?float
    {
        if (!$this->scheduledAt) {
            return null;
        }

        $microseconds = bcsub(bcadd($this->scheduledAt, $this->getDurationMicrosecond(), 4), microtime(true), 4);
        return (float)bcdiv($microseconds, 1000000, 4);
    }

    private function getDurationMicrosecond(): int
    {
        return (int)bcmul($this->duration, 1000000, 0);
    }
}