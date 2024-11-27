<?php

namespace App\Entity;

use App\Repository\RecipeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: RecipeRepository::class)]
#[ORM\Table(name: 'game_recipe')]
class Recipe
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    /**
     * @var Collection<int, RecipeIngredient>
     */
    #[ORM\OneToMany(targetEntity: RecipeIngredient::class, mappedBy: 'recipe')]
    private Collection $ingredients;

    public function __construct(
        #[ORM\Column(type: 'string', length: 50, enumType: Skill::class)]
        private readonly Skill $skill,
        #[ORM\Column(type: 'float')]
        private readonly float $minExperienceRequired,
        #[ORM\Column(type: 'float')]
        private readonly float $experienceReward,
        #[ORM\OneToOne(targetEntity: Item::class)]
        private readonly Item $producedItem
    )
    {
        $this->ingredients = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getSkill(): Skill
    {
        return $this->skill;
    }

    public function getMinExperienceRequired(): float
    {
        return $this->minExperienceRequired;
    }

    public function getExperienceReward(): float
    {
        return $this->experienceReward;
    }

    /**
     * @return Collection<int, RecipeIngredient>
     */
    public function getIngredients(): Collection
    {
        return $this->ingredients;
    }

    public function addIngreient(RecipeIngredient $ingredient): static
    {
        if (!$this->ingredients->contains($ingredient)) {
            $this->ingredients->add($ingredient);
            $ingredient->setRecipe($this);
        }

        return $this;
    }

    public function removeIngredient(RecipeIngredient $ingredient): static
    {
        if ($this->ingredients->removeElement($ingredient)) {
            // set the owning side to null (unless already changed)
            if ($ingredient->getRecipe() === $this) {
                $ingredient->setRecipe(null);
            }
        }

        return $this;
    }

    public function getProducedItem(): Item
    {
        return $this->producedItem;
    }
}
