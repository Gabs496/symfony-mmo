<?php

namespace App\Entity\Game;

use App\Entity\Skill;
use App\Repository\ResourceRepository;
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
    private float $difficulty = 0.0;

    #[ORM\Column(enumType: Skill::class)]
    private ?Skill $skillNeeded = null;

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

    public function getSkillNeeded(): ?Skill
    {
        return $this->skillNeeded;
    }

    public function setSkillNeeded(Skill $skillNeeded): static
    {
        $this->skillNeeded = $skillNeeded;

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
}
