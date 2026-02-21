<?php

namespace App\GameElement\Position\Component;

use Attribute;
use Doctrine\ORM\Mapping as ORM;
use PennyPHP\Core\Entity\GameComponent;
use PennyPHP\Core\Entity\GameObject;

/** @deprecated  */
#[Attribute(Attribute::TARGET_CLASS)]
#[ORM\Index(fields: ['placeType', 'placeId'])]
class PlacedComponent extends GameComponent
{
    public function __construct(
        ?GameObject       $gameObject = null,
        #[ORM\Column(length: 50, nullable: false)]
        protected ?string $placeType = null,
        #[ORM\Column(length: 100, nullable: false)]
        protected ?string $placeId = null,
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
}
