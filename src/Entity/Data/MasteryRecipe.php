<?php

namespace App\Entity\Data;

use App\Entity\Game\Recipe;
use App\Entity\Interface\MasteryInterface;
use App\Repository\MasteryRecipeRepository;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: MasteryRecipeRepository::class)]
class MasteryRecipe implements MasteryInterface
{
    #[ORM\Id]
    #[ORM\Column(type: 'guid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?string $id = null;

    #[ORM\ManyToOne(targetEntity: Mastery::class ,inversedBy: 'recipes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Mastery $masteryCollection = null;

    #[ORM\Column]
    private float $experience = 0.0;

    #[ORM\ManyToOne(targetEntity: Recipe::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Recipe $recipe;

    public function getId(): ?string
    {
        return $this->id;
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

    public function getExperience(): float
    {
        return $this->experience;
    }

    public function setExperience(float $experience): static
    {
        $this->experience = $experience;

        return $this;
    }

    public function getRecipe(): Recipe
    {
        return $this->recipe;
    }

    public function setRecipe(Recipe $recipe): void
    {
        $this->recipe = $recipe;
    }
}
