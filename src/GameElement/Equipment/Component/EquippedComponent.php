<?php

namespace App\GameElement\Equipment\Component;

use App\GameElement\Equipment\Repository\EquippedRepository;
use Attribute;
use Doctrine\ORM\Mapping\Entity;
use PennyPHP\Core\Entity\GameComponent;
use PennyPHP\Core\GameObjectInterface;

#[Attribute(Attribute::TARGET_CLASS)]
#[Entity(repositoryClass: EquippedRepository::class)]
class EquippedComponent extends GameComponent
{
    private string $equipmentSetId;
    public function __construct(
        GameObjectInterface $equipmentSet,
        private string $slot,
    )
    {
        parent::__construct();
        $this->equipmentSetId = $equipmentSet->getId();
    }

    public function getEquipmentSetId(): string
    {
        return $this->equipmentSetId;
    }

    public function setEquipmentSetId(string $equipmentSetId): void
    {
        $this->equipmentSetId = $equipmentSetId;
    }

    public function getSlot(): string
    {
        return $this->slot;
    }

    public function setSlot(string $slot): void
    {
        $this->slot = $slot;
    }
}