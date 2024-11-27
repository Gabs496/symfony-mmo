<?php

namespace App\Entity\Data;

use App\Entity\Interface\MasteryInterface;
use App\Entity\Skill;
use App\Repository\MasterySkillRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: MasterySkillRepository::class)]
class MasterySkill implements MasteryInterface
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\Column]
    private float $experience = 0.0;

    #[ORM\ManyToOne(inversedBy: 'skills')]
    #[ORM\JoinColumn(nullable: false)]
    private ?MasteryCollection $masteryCollection = null;

    #[ORM\Column(type: 'string', length: 50, enumType: Skill::class)]
    private Skill $skill;

    public function getId(): ?Uuid
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

    public function getMasteryCollection(): ?MasteryCollection
    {
        return $this->masteryCollection;
    }

    public function setMasteryCollection(?MasteryCollection $masteryCollection): static
    {
        $this->masteryCollection = $masteryCollection;

        return $this;
    }
}
