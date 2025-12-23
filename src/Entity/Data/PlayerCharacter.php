<?php

namespace App\Entity\Data;

use App\Engine\Player\PlayerCombatManager;
use App\Entity\Activity\Activity;
use App\Entity\Core\GameObject;
use App\Entity\Security\User;
use App\GameElement\Combat\Component\CombatComponent;
use App\GameElement\Combat\Component\Stat\PhysicalAttackStat;
use App\GameElement\Combat\StatCollection;
use App\GameElement\Core\GameComponent\GameComponentInterface;
use App\GameElement\Core\GameObject\GameObjectInterface;
use App\GameElement\Map\AbstractMap;
use App\GameElement\Mastery\MasterySet;
use App\GameObject\Mastery\Combat\PhysicalAttack;
use App\Repository\Data\PlayerCharacterRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;


#[ORM\Entity(repositoryClass: PlayerCharacterRepository::class)]
#[UniqueEntity(fields: ['name'], message: 'This name is already taken.')]
#[ORM\UniqueConstraint(columns: ['name'])]
class PlayerCharacter implements UserInterface
{
    #[ORM\Id]
    #[ORM\Column(type: 'guid', unique: true)]
    protected string $id;

    #[ORM\Column(length: 50)]
    protected ?string $name = null;

    #[ORM\OneToOne(targetEntity: BackpackItemBag::class, inversedBy: 'player', cascade: ['persist', 'remove'])]
    protected BackpackItemBag $backpack;

    #[ORM\OneToOne(targetEntity: EquippedItemBag::class, inversedBy: 'player', cascade: ['persist', 'remove'])]
    protected EquippedItemBag $equipment;

    #[ORM\Column(type: 'json_document', nullable: true, options: ['jsonb' => true])]
    private MasterySet $masterySet;

    #[ORM\ManyToOne(inversedBy: 'playerCharacters')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;
    private array $roles = [];

    /** @var AbstractMap  */
    #[ORM\Column(name: 'position', type: 'game_object', nullable: true)]
    private GameObjectInterface $map;

    #[ORM\ManyToOne(targetEntity: Activity::class, cascade: ['all'])]
    #[ORM\JoinColumn(nullable: true)]
    private ?Activity $currentActivity;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private GameObject $gameObject;

    public function __construct(GameObject $gameObject)
    {
        $this->id = Uuid::v7()->toString();
        $this->masterySet = new MasterySet();
        $this->backpack = new BackpackItemBag($this);
        $this->equipment = new EquippedItemBag($this);
        $this->gameObject = $gameObject;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): PlayerCharacter
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

    public function getBackpack(): BackpackItemBag
    {
        return $this->backpack;
    }

    public function getEquipment(): EquippedItemBag
    {
        return $this->equipment;
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

    public function getGameObject(): GameObject
    {
        return $this->gameObject;
    }

    public function getCombatComponent(): CombatComponent
    {
        $statCollection  = new StatCollection();
        $statCollection->increase(PhysicalAttackStat::class, $this->getMasteryExperience(PhysicalAttack::getId()));

        foreach ($this->equipment->getItems() as $equipmentItem) {
            if ($combat = $equipmentItem->getGameObject()->getComponent(CombatComponent::class)) {
                /** @var CombatComponent $combat */
                foreach ($combat->getStats() as $stat) {
                    $statCollection->increase($stat::class, $stat->getValue());
                }
            }
        }
        return new CombatComponent($statCollection->getStats(), PlayerCombatManager::getId());
    }

    public function getComponents(): array
    {
        return [
            CombatComponent::getId() => $this->getCombatComponent(),
        ];
    }

    /** @param class-string<GameComponentInterface> $componentClass */
    public function hasComponent(string $componentClass): bool
    {
        return isset($this->getComponents()[$componentClass::getId()]);
    }

    /**
     * @template T of GameComponentInterface
     * @param class-string<T> $componentClass
     * @return T|null
     */
    public function getComponent(string $componentClass): ?GameComponentInterface
    {
        return $this->getComponents()[$componentClass::getId()] ?? null;
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
