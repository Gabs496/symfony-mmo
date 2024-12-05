<?php

namespace App\Entity\Game;

use App\Repository\Game\RecipeIngredientRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RecipeIngredientRepository::class)]
class RecipeIngredient
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 50)]
    private ?string $id = null;

    public function __construct(
        #[ORM\ManyToOne(targetEntity: Recipe::class, inversedBy: 'ingredients')]
        #[ORM\JoinColumn(nullable: false)]
        private Recipe $recipe,
        #[ORM\ManyToOne(targetEntity: Item::class)]
        #[ORM\JoinColumn(nullable: false)]
        private readonly Item   $item,
        #[ORM\Column(type: 'integer')]
        private readonly int $quantity
    )
    {
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getRecipe(): Recipe
    {
        return $this->recipe;
    }

    public function setRecipe(Recipe $recipe): self
    {
        $this->recipe = $recipe;
        return $this;
    }

    public function getItem(): Item
    {
        return $this->item;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }
}
