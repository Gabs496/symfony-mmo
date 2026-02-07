<?php

namespace App\GameElement\Activity;

use PennyPHP\Core\GameObject\GameObjectInterface;

abstract class AbstractActivity
{
    protected string $subjectToken;
    protected ?GameObjectInterface $subject;
    /** Duration in seconds */
    protected string $entityId;

    public function __construct(GameObjectInterface $subject)
    {
        $this->subject = $subject;
        $this->subjectToken = $subject->getId();
    }

    public function getSubjectToken(): string
    {
        return $this->subjectToken;
    }

    public function getSubject(): GameObjectInterface
    {
        return $this->subject;
    }

    public function setSubject(?GameObjectInterface $subject): void
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

    public function clear(): void
    {
        $this->subject = null;
    }

    public function getId(): string
    {
        return $this::class;
    }
}