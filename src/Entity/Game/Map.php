<?php

namespace App\Entity\Game;

use App\Entity\Data\MapAvailableActivity;
use App\Entity\Data\PlayerCharacter;
use App\Repository\Game\MapRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MapRepository::class)]
class Map
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 50)]
    private ?string $id = null;

    private ?string $name = null;

    #[ORM\Column]
    private ?float $coordinateX = null;

    #[ORM\Column]
    private ?float $coordinateY = null;

    /**
     * @var Collection<int, MapResource>
     */
    #[ORM\OneToMany(targetEntity: MapResource::class, mappedBy: 'map', orphanRemoval: true)]
    private Collection $spawnableResources;

    /**
     * @var Collection<int, MapAvailableActivity>
     */
    #[ORM\OneToMany(targetEntity: MapAvailableActivity::class, mappedBy: 'map', orphanRemoval: true)]
    private Collection $availableActivities;

    /**
     * @var Collection<int, PlayerCharacter>
     */
    #[ORM\OneToMany(targetEntity: PlayerCharacter::class, mappedBy: 'position')]
    private Collection $playerCharacters;

    public function __construct()
    {
        $this->spawnableResources = new ArrayCollection();
        $this->availableActivities = new ArrayCollection();
        $this->playerCharacters = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getCoordinateX(): ?float
    {
        return $this->coordinateX;
    }

    public function setCoordinateX(float $coordinateX): static
    {
        $this->coordinateX = $coordinateX;

        return $this;
    }

    public function getCoordinateY(): ?float
    {
        return $this->coordinateY;
    }

    public function setCoordinateY(float $coordinateY): static
    {
        $this->coordinateY = $coordinateY;

        return $this;
    }

    /**
     * @return Collection<int, MapResource>
     */
    public function getSpawnableResources(): Collection
    {
        return $this->spawnableResources;
    }

    public function addSpawnableResource(MapResource $spawnableResource): static
    {
        if (!$this->spawnableResources->contains($spawnableResource)) {
            $this->spawnableResources->add($spawnableResource);
            $spawnableResource->setMap($this);
        }

        return $this;
    }

    public function removeSpawnableResource(MapResource $spawnableResource): static
    {
        if ($this->spawnableResources->removeElement($spawnableResource)) {
            // set the owning side to null (unless already changed)
            if ($spawnableResource->getMap() === $this) {
                $spawnableResource->setMap(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, PlayerCharacter>
     */
    public function getPlayerCharacters(): Collection
    {
        return $this->playerCharacters;
    }

    public function addPlayerCharacter(PlayerCharacter $playerCharacter): static
    {
        if (!$this->playerCharacters->contains($playerCharacter)) {
            $this->playerCharacters->add($playerCharacter);
            $playerCharacter->setPosition($this);
        }

        return $this;
    }

    public function removePlayerCharacter(PlayerCharacter $playerCharacter): static
    {
        if ($this->playerCharacters->removeElement($playerCharacter)) {
            // set the owning side to null (unless already changed)
            if ($playerCharacter->getPosition() === $this) {
                $playerCharacter->setPosition(null);
            }
        }

        return $this;
    }

    public function getAvailableActivities(): Collection
    {
        return $this->availableActivities;
    }
}
