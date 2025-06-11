<?php

namespace App\GameElement\Activity;

use App\GameElement\Core\Token\TokenInterface;
use App\GameElement\Core\Token\TokenizableInterface;

abstract class AbstractActivity
{
    protected TokenInterface $subjectToken;
    protected ?TokenizableInterface $subject;
    /** Duration in seconds */
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

    public function clear(): void
    {
        $this->subject = null;
    }

    public function getId(): string
    {
        return $this::class;
    }
}