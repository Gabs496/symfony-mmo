<?php

namespace App\Entity\Activity;

use App\Repository\Data\ActivityRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ActivityRepository::class)]
class Activity
{
    #[ORM\Id]
    #[ORM\Column(type: 'guid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?string $id = null;

    #[ORM\Column(type: 'string')]
    protected string $type;

    /**
     * Duration in seconds
     */
    #[ORM\Column(type: 'float', nullable: false)]
    private float $duration;

    #[ORM\Column(type: 'float', nullable: false)]
    private float $scheduledAt;

    #[ORM\Column(type: 'float', nullable: true)]
    protected ?float $startedAt = null;

    #[ORM\Column(type: 'float', nullable: true)]
    private ?float $completedAt = null;

    public function __construct(string $type, float $duration)
    {
        $this->type = $type;
        $this->duration = $duration;
        $this->scheduledAt = microtime(true);
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getStartedAt(): ?float
    {
        return $this->startedAt;
    }

    public function getDuration(): float
    {
        return $this->duration;
    }

    public function setDuration(float $duration): void
    {
        $this->duration = $duration;
    }

    public function getCompletedAt(): ?float
    {
        return $this->completedAt;
    }

    public function setCompletedAt(?float $completedAt): void
    {
        $this->completedAt = $completedAt;
    }

    public function start(): void
    {
        $this->startedAt = microtime(true);
    }

    public function getSecondsToFinish(): ?float
    {
        $scheduledEnd = bcadd($this->scheduledAt, $this->duration, 10);
        return bcsub($scheduledEnd, microtime(true), 4);
    }

    public function shouldBeFinished(): bool
    {
        if ($this->completedAt !== null) {
            return true;
        }

        return round($this->getSecondsToFinish(), 4) < 0.0000;
    }
}