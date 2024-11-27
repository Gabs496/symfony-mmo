<?php

namespace App\Entity;

use App\Repository\RecipeIngredientRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: RecipeIngredientRepository::class)]
#[ORM\Table(name: 'game_recipe_ingredient')]
class RecipeIngredient
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

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

    public function getId(): ?Uuid
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
