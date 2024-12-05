<?php

namespace App\Entity\Game;

use App\Entity\MasteryType;
use App\Repository\Game\ResourceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ResourceRepository::class)]
class Resource
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 50)]
    private ?string $id = null;

    #[ORM\OneToOne(targetEntity: Item::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?Item $product = null;

    #[ORM\Column]
    private float $difficulty = 999.0;

    #[ORM\Column]
    private float $gatheringTime = 3600.0;

    #[ORM\Column(enumType: MasteryType::class)]
    private ?MasteryType $masteryInvolved = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getDifficulty(): ?float
    {
        return $this->difficulty;
    }

    public function setDifficulty(float $difficulty): static
    {
        $this->difficulty = $difficulty;

        return $this;
    }

    public function getMasteryInvolved(): ?MasteryType
    {
        return $this->masteryInvolved;
    }

    public function setMasteryInvolved(MasteryType $masteryInvolved): static
    {
        $this->masteryInvolved = $masteryInvolved;

        return $this;
    }

    public function getProduct(): ?Item
    {
        return $this->product;
    }

    public function setProduct(?Item $product): void
    {
        $this->product = $product;
    }

    public function getName(): string
    {
        return $this->product->getName();
    }

    public function getIcon(): string
    {
        return $this->product->getIcon();
    }

    public function getGatheringTime(): float
    {
        return $this->gatheringTime;
    }

    public function setGatheringTime(float $gatheringTime): void
    {
        $this->gatheringTime = $gatheringTime;
    }
}
