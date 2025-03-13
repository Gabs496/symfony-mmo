<?php

namespace App\Entity\Data;

use App\Entity\Security\User;
use App\GameElement\Character\AbstractCharacter;
use App\GameElement\Core\GameObject\GameObjectReference;
use App\GameElement\Map\AbstractMap;
use App\GameElement\Mastery\MasterySet;
use App\GameElement\Mastery\MasteryType;
use App\Repository\Data\PlayerCharacterRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;


#[ORM\Entity(repositoryClass: PlayerCharacterRepository::class)]
#[UniqueEntity(fields: ['name'], message: 'This name is already taken.')]
#[ORM\UniqueConstraint(columns: ['name'])]
class PlayerCharacter extends AbstractCharacter implements UserInterface
{
    #[ORM\Id]
    #[ORM\Column(type: 'guid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?string $id = null;

    #[ORM\Column(length: 50)]
    protected ?string $name = null;

    #[ORM\OneToOne(targetEntity: BackpackItemBag::class, inversedBy: 'player', cascade: ['persist', 'remove'])]
    protected BackpackItemBag $backpack;

    #[ORM\Column(type: 'json_document', nullable: true, options: ['jsonb' => true])]
    private MasterySet $masterySet;

    #[ORM\ManyToOne(inversedBy: 'playerCharacters')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $position = null;
    private array $roles = [];

    #[GameObjectReference(AbstractMap::class, objectIdProperty: 'position')]
    private AbstractMap $map;

    #[ORM\ManyToOne(targetEntity: Activity::class, cascade: ['all'])]
    #[ORM\JoinColumn(nullable: true)]
    private ?Activity $currentActivity;

    public function __construct()
    {
        $this->masterySet = new MasterySet();
        $this->backpack = new BackpackItemBag($this);
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

    public function getMasteryExperience(MasteryType $masteryType): float
    {
        return $this->masterySet->getMastery($masteryType)->getExperience();
    }

    public function increaseMasteryExperience(MasteryType $masteryType, float $experience): static
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

    public function getPosition(): ?string
    {
        return $this->position;
    }

    public function setPosition(?string $position): static
    {
        $this->position = $position;

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
        return;
    }

    public function getUserIdentifier(): string
    {
        return $this->getName();
    }

    public function getBackpack(): BackpackItemBag
    {
        return $this->backpack;
    }

    public function cloneMasterySet(): MasterySet
    {
        return $this->masterySet = clone $this->masterySet;
    }

    public function getMasterySet(): MasterySet
    {
        return ($this->masterySet ??= new MasterySet());
    }

    public function setMap(AbstractMap $map): void
    {
        $this->map = $map;
    }
    public function getMap(): AbstractMap
    {
        return $this->map;
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
}
