<?php

namespace App\Entity\Data;

use App\Entity\Interface\MasteryInterface;
use App\Entity\Skill;
use App\Repository\MasterySkillRepository;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: MasterySkillRepository::class)]
class MasterySkill implements MasteryInterface
{
    #[ORM\Id]
    #[ORM\Column(type: 'guid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?string $id = null;

    #[ORM\Column]
    private float $experience = 0.0;

    #[ORM\ManyToOne(inversedBy: 'skills')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Mastery $masteryCollection = null;

    #[ORM\Column(type: 'string', length: 50, enumType: Skill::class)]
    private Skill $skill;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getExperience(): float
    {
        return $this->experience;
    }

    public function setExperience(float $experience): static
    {
        $this->experience = $experience;

        return $this;
    }

    public function getSkill(): Skill
    {
        return $this->skill;
    }

    public function setSkill(Skill $skill): void
    {
        $this->skill = $skill;
    }

    public function getMasteryCollection(): ?Mastery
    {
        return $this->masteryCollection;
    }

    public function setMasteryCollection(?Mastery $masteryCollection): static
    {
        $this->masteryCollection = $masteryCollection;

        return $this;
    }
}
