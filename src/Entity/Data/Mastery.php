<?php

namespace App\Entity\Data;

use App\Repository\MasteryCollectionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: MasteryCollectionRepository::class)]
class Mastery
{
    #[ORM\Id]
    #[ORM\Column(type: 'guid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?string $id = null;

    /**
     * @var Collection<int, MasterySkill>
     */
    #[ORM\OneToMany(targetEntity: MasterySkill::class, mappedBy: 'masteryCollection', orphanRemoval: true)]
    private Collection $skills;

    /**
     * @var Collection<int, MasteryRecipe>
     */
    #[ORM\OneToMany(targetEntity: MasteryRecipe::class, mappedBy: 'masteryCollection', orphanRemoval: true)]
    private Collection $recipes;

    /**
     * @var Collection<int, MasteryItemType>
     */
    #[ORM\OneToMany(targetEntity: MasteryItemType::class, mappedBy: 'masteryCollection', orphanRemoval: true)]
    private Collection $itemTypes;

    /**
     * @var Collection<int, MasteryItem>
     */
    #[ORM\OneToMany(targetEntity: MasteryItem::class, mappedBy: 'masteryCollection', orphanRemoval: true)]
    private Collection $items;

    public function __construct()
    {
        $this->skills = new ArrayCollection();
        $this->recipes = new ArrayCollection();
        $this->itemTypes = new ArrayCollection();
        $this->items = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @return Collection<int, MasterySkill>
     */
    public function getSkills(): Collection
    {
        return $this->skills;
    }

    public function addSkill(MasterySkill $skill): static
    {
        if (!$this->skills->contains($skill)) {
            $this->skills->add($skill);
            $skill->setMasteryCollection($this);
        }

        return $this;
    }

    public function removeSkill(MasterySkill $skill): static
    {
        if ($this->skills->removeElement($skill)) {
            // set the owning side to null (unless already changed)
            if ($skill->getMasteryCollection() === $this) {
                $skill->setMasteryCollection(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, MasteryRecipe>
     */
    public function getRecipes(): Collection
    {
        return $this->recipes;
    }

    public function addRecipe(MasteryRecipe $recipe): static
    {
        if (!$this->recipes->contains($recipe)) {
            $this->recipes->add($recipe);
            $recipe->setMasteryCollection($this);
        }

        return $this;
    }

    public function removeRecipe(MasteryRecipe $recipe): static
    {
        if ($this->recipes->removeElement($recipe)) {
            // set the owning side to null (unless already changed)
            if ($recipe->getMasteryCollection() === $this) {
                $recipe->setMasteryCollection(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, MasteryItemType>
     */
    public function getItemTypes(): Collection
    {
        return $this->itemTypes;
    }

    public function addItemType(MasteryItemType $itemType): static
    {
        if (!$this->itemTypes->contains($itemType)) {
            $this->itemTypes->add($itemType);
            $itemType->setMasteryCollection($this);
        }

        return $this;
    }

    public function removeItemType(MasteryItemType $itemType): static
    {
        if ($this->itemTypes->removeElement($itemType)) {
            // set the owning side to null (unless already changed)
            if ($itemType->getMasteryCollection() === $this) {
                $itemType->setMasteryCollection(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, MasteryItem>
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(MasteryItem $item): static
    {
        if (!$this->items->contains($item)) {
            $this->items->add($item);
            $item->setMasteryCollection($this);
        }

        return $this;
    }

    public function removeItem(MasteryItem $item): static
    {
        if ($this->items->removeElement($item)) {
            // set the owning side to null (unless already changed)
            if ($item->getMasteryCollection() === $this) {
                $item->setMasteryCollection(null);
            }
        }

        return $this;
    }
}