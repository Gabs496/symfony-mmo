<?php

namespace App\Entity\Data;

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

    public function setStartedAt(?float $startedAt): void
    {
        $this->startedAt = $startedAt;
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

    public function getSecondsToFinish(): ?float
    {
        $microseconds = bcsub(bcadd($this->scheduledAt, $this->getDurationMicrosecond(), 4), microtime(true), 4);
        return (float)bcdiv($microseconds, 1000000, 4);
    }

    private function getDurationMicrosecond(): int
    {
        return (int)bcmul($this->duration, 1000000, 0);
    }
}