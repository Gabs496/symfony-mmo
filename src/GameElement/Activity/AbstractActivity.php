<?php

namespace App\GameElement\Activity;

use DateTimeImmutable;

abstract class AbstractActivity
{
    /**
     * Duration in seconds
     */
    private ?float $duration = null;
    private bool $isCompleted = false;

    private ?DateTimeImmutable $scheduledAt = null;

    protected string $entityId;

    public function getEntityId(): string
    {
        return $this->entityId;
    }

    public function setEntityId(string $entityId): void
    {
        $this->entityId = $entityId;
    }

    public function getDuration(): ?float
    {
        return $this->duration;
    }

    public function setDuration(float $duration): void
    {
        $this->duration = $duration;
    }

    public function isCompleted(): bool
    {
        return $this->isCompleted;
    }

    public function setIsCompleted(bool $isCompleted): void
    {
        $this->isCompleted = $isCompleted;
    }

    public function getScheduledAt(): ?DateTimeImmutable
    {
        return $this->scheduledAt;
    }

    public function start(): void
    {
        $this->scheduledAt = new DateTimeImmutable();
    }
}