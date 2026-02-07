<?php

namespace App\Entity\Data;

use App\Entity\Activity\Activity;
use App\Entity\Security\User;
use PennyPHP\Core\GameComponent\GameComponent;
use App\GameElement\Map\Component\MapComponent;
use App\GameElement\Mastery\MasterySet;
use App\GameElement\Position\Component\PositionComponent;
use App\Repository\Data\PlayerCharacterRepository;
use Attribute;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: PlayerCharacterRepository::class)]
#[UniqueEntity(fields: ['name'], message: 'This name is already taken.')]
#[ORM\UniqueConstraint(columns: ['name'])]
#[ORM\Table(name: "data_player_character")]
#[Attribute(Attribute::TARGET_CLASS)]
class Player extends GameComponent implements UserInterface
{
    #[ORM\Column(length: 50)]
    protected ?string $name = null;

    #[ORM\Column(type: 'json_document', nullable: true, options: ['jsonb' => true])]
    private MasterySet $masterySet;

    #[ORM\ManyToOne(inversedBy: 'playerCharacters')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;
    private array $roles = [];

    #[ORM\ManyToOne(targetEntity: Activity::class, cascade: ['all'])]
    #[ORM\JoinColumn(nullable: true)]
    private ?Activity $currentActivity;

    public function __construct()
    {
        $this->masterySet = new MasterySet();
        parent::__construct();
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): Player
    {
        $this->name = $name;

        return $this;
    }

    public function getMasteryExperience(string $masteryType): float
    {
        return $this->masterySet->getMastery($masteryType)->getExperience();
    }

    public function increaseMasteryExperience(string $masteryType, float $experience): static
    {
        $this->masterySet->increaseMasteryExperience($masteryType, $experience);

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function eraseCredentials(): void
    {
    }

    public function getUserIdentifier(): string
    {
        return $this->getName();
    }

    public function cloneMasterySet(): MasterySet
    {
        return $this->masterySet = clone $this->masterySet;
    }

    public function getMasterySet(): MasterySet
    {
        return ($this->masterySet ??= new MasterySet());
    }

    public function getCurrentActivity(): ?Activity
    {
        return $this->currentActivity;
    }

    public function startActivity(Activity $activity): void
    {
        $this->currentActivity = $activity;
    }

    public function endCurrentActivity(): void
    {
        $this->currentActivity = null;
    }

    public function isInvolvedInActivity(?Activity $activity = null): bool
    {
        if (!$this->currentActivity) {
            return false;
        }

        return $this->currentActivity === $activity;
    }

    public function getMap(): MapComponent
    {
        return $this->gameObject->getComponent(PositionComponent::class)->getPlaceId()->getComponent(MapComponent::class);
    }

    public function __serialize(): array
    {
        return [
            'id' => $this->id,
            'roles' => $this->roles,
            'name' => $this->name,
        ];
    }
}
