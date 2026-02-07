<?php

namespace App\GameElement\Position\Component;

use App\GameElement\Core\GameComponent\GameComponent;
use App\GameElement\Core\GameObject\Entity\GameObject;
use App\GameElement\Position\Repository\PositionRepository;
use Attribute;
use Doctrine\ORM\Mapping as ORM;

#[Attribute(Attribute::TARGET_CLASS)]
#[ORM\Entity(repositoryClass: PositionRepository::class)]
#[ORM\Index(fields: ['placeType', 'placeId'])]
class PositionComponent extends GameComponent
{
    public function __construct(
        ?GameObject       $gameObject = null,
        #[ORM\Column(length: 50, nullable: false)]
        protected ?string $placeType = null,
        #[ORM\Column(length: 100, nullable: false)]
        protected ?string $placeId = null,
        #[ORM\Column(length: 50)]
        protected ?string $position = null,
    )
    {
        parent::__construct($gameObject);
    }

    public function getPlaceType(): ?string
    {
        return $this->placeType;
    }

    public function setPlaceType(string $placeType): self
    {
        $this->placeType = $placeType;

        return $this;
    }

    public function getPlaceId(): string
    {
        return $this->placeId;
    }

    public function setPlaceId(string $placeId): self
    {
        $this->placeId = $placeId;

        return $this;
    }

    public function getPosition(): ?string
    {
        return $this->position;
    }

    public function setPosition(?string $position): void
    {
        $this->position = $position;
    }
}
