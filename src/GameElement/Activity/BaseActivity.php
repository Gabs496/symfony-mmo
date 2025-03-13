<?php

namespace App\GameElement\Activity;

use DateTimeImmutable;

abstract class BaseActivity
{
    /**
     * Duration in seconds
     */
    private ?float $duration = null;
    private bool $isCompleted = false;

    private ?DateTimeImmutable $scheduledAt = null;

    protected \App\Entity\Data\Activity $entity;

    public function getEntity(): \App\Entity\Data\Activity
    {
        return $this->entity;
    }

    public function setEntity(\App\Entity\Data\Activity $entity): void
    {
        $this->entity = $entity;
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