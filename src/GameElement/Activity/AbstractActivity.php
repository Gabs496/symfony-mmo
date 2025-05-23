<?php

namespace App\GameElement\Activity;

use App\GameElement\Core\Token\TokenInterface;
use App\GameElement\Core\Token\TokenizableInterface;
use DateTimeImmutable;

abstract class AbstractActivity
{
    protected TokenInterface $subjectToken;
    protected ?TokenizableInterface $subject;
    /** Duration in seconds */
    protected ?float $duration = null;
    protected bool $isCompleted = false;

    protected ?DateTimeImmutable $scheduledAt = null;

    protected string $entityId;

    public function __construct(TokenizableInterface $subject)
    {
        $this->subject = $subject;
        $this->subjectToken = $subject->getToken();
    }

    public function getSubjectToken(): TokenInterface
    {
        return $this->subjectToken;
    }

    public function getSubject(): TokenizableInterface
    {
        return $this->subject;
    }

    public function setSubject(?TokenizableInterface $subject): void
    {
        $this->subject = $subject;
    }

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