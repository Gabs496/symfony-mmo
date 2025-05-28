<?php

namespace App\Entity\Data;

use App\Engine\Player\PlayerCombatManager;
use App\Engine\Player\PlayerToken;
use App\Entity\Security\User;
use App\GameElement\Character\AbstractCharacter;
use App\GameElement\Combat\Component\Combat;
use App\GameElement\Combat\Component\Stat\PhysicalAttackStat;
use App\GameElement\Combat\HasCombatComponentInterface;
use App\GameElement\Combat\StatCollection;
use App\GameElement\Core\GameObject\GameObjectReference;
use App\GameElement\Core\Token\TokenizableInterface;
use App\GameElement\Health\Component\Health;
use App\GameElement\Health\HasHealthComponentInterface;
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
class PlayerCharacter extends AbstractCharacter
    implements UserInterface, TokenizableInterface, HasHealthComponentInterface, HasCombatComponentInterface
{
    #[ORM\Id]
    #[ORM\Column(type: 'guid', unique: true)]
    private string $id;

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

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $position = null;
    private array $roles = [];

    #[GameObjectReference(AbstractMap::class, objectIdProperty: 'position')]
    private AbstractMap $map;

    #[ORM\ManyToOne(targetEntity: Activity::class, cascade: ['all'])]
    #[ORM\JoinColumn(nullable: true)]
    private ?Activity $currentActivity;

    #[ORM\Column(type: 'json_document', nullable: false)]
    private Health $health;

    public function __construct()
    {
        $this->id = Uuid::v7()->toString();
        $this->masterySet = new MasterySet();
        $this->backpack = new BackpackItemBag($this);
        $this->equipment = new EquippedItemBag($this);
        $this->health = new Health(0.25, 0.25);
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

    public function getHealth(): Health
    {
        return $this->health;
    }

    public function setHealth(Health $health): void
    {
        $this->health = $health;
    }

    public function getCurrentHealth(): float
    {
        return $this->health->getCurrentHealth();
    }

    public function getCombatComponent(): Combat
    {
        $statCollection  = new StatCollection();
        $statCollection->increase(PhysicalAttackStat::class, $this->getMasteryExperience(PhysicalAttack::getId()));

        foreach ($this->equipment->getItems() as $equipmentItem) {
            if ($combat = $equipmentItem->getComponent(Combat::class)) {
                /** @var Combat $combat */
                foreach ($combat->getStats() as $stat) {
                    $statCollection->increase($stat::class, $stat->getValue());
                }
            }
        }
        return new Combat($statCollection->getStats());
    }

    public static function getCombatManagerClass(): string
    {
        return PlayerCombatManager::class;
    }

    public function getToken(): PlayerToken
    {
        return new PlayerToken($this->id);
    }
}
