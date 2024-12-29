<?php

namespace App\Entity\Data;

use App\Entity\AbstractCharacter;
use App\Entity\Game\Map;
use App\Entity\ItemBagType;
use App\Entity\MasterySet;
use App\Entity\MasteryType;
use App\Entity\Security\User;
use App\Repository\Data\PlayerCharacterRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    /**
     * @var Collection<int, ItemInstanceBag>
     */
    #[ORM\OneToMany(targetEntity: ItemInstanceBag::class, mappedBy: 'player', cascade: ['persist', 'remove'])]
    protected Collection $itemInstanceBags;

    #[ORM\Column(type: 'json_document', nullable: true, options: ['jsonb' => true])]
    private MasterySet $masterySet;

    #[ORM\ManyToOne(inversedBy: 'playerCharacters')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $position = null;
    private array $roles = [];

    public function __construct()
    {
        $this->itemInstanceBags = new ArrayCollection();
        $this->masterySet = new MasterySet();
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

    public function getItemBag(ItemBagType $itemBagType): ItemInstanceBag
    {
        foreach ($this->itemInstanceBags as $itemBag) {
            if ($itemBag->is($itemBagType)) {
                return $itemBag;
            }
        }
        $itemBag = new ItemInstanceBag($itemBagType, $this);
        $this->itemInstanceBags->add($itemBag);
        return $itemBag;
    }

    public function addToItemBag(ItemBagType $itemBagType, ItemInstance $itemInstance): static
    {
        $this->getItemBag($itemBagType)->addItem($itemInstance);
        return $this;
    }

    public function takeItem(ItemInstance $itemInstance): void
    {
        $this->addToItemBag(ItemBagType::BACKPACK, $itemInstance);
    }

    public function cloneMasterySet(): MasterySet
    {
        return $this->masterySet = clone $this->masterySet;
    }

    public function getMasterySet(): MasterySet
    {
        return ($this->masterySet ??= new MasterySet());
    }
}
