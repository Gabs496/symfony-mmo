<?php

namespace App\Entity\Data;

use App\Entity\ActivityStep;
use App\GameElement\Activity\ActivityInterface;
use App\GameElement\Mastery\MasterySet;
use App\GameObject\Activity\ActivityType;
use App\Repository\Data\ActivityRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
//use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: ActivityRepository::class)]
//#[Broadcast(topics: ['@="playerActivity"'], private: true, template: 'map/PlayerActivity.stream.html.twig')]
class Activity implements ActivityInterface
{
    #[ORM\Id]
    #[ORM\Column(type: 'guid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?string $id = null;

    #[ORM\Column(type: 'string', enumType: ActivityType::class)]
    protected ActivityType $type;

    /** @var ActivityStep[] */
    #[ORM\Column(type: 'json_document', options: ['jsonb' => true])]
    protected array $steps = [];

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    protected ?DateTimeImmutable $startedAt = null;

    /** @var Collection<int, MapAvailableActivity> */
    #[ORM\OneToMany(targetEntity: MapAvailableActivity::class, mappedBy: 'involvingActivity')]
    protected Collection $mapAvailableActivities;

    /**
     * @param ActivityType $type
     */
    public function __construct(ActivityType $type)
    {
        $this->type = $type;
        $this->mapAvailableActivities = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getType(): ActivityType
    {
        return $this->type;
    }

    public function getSteps(): array
    {
        return $this->steps;
    }

    public function addStep(ActivityStep $step): static
    {
        $this->steps[] = $step;

        return $this;
    }

    /** @param MasterySet $masterySet */
    public function applyMasteryPerformance(MasterySet $masterySet)
    {
        // TODO: decidere come applicare le performance delle abilità
    }

    public function getNextStep(): ?ActivityStep
    {
        return $this->steps[0] ?? null;
    }

    public function getStartedAt(): ?DateTimeImmutable
    {
        return $this->startedAt;
    }

    public function setStartedAt(?DateTimeImmutable $startedAt): void
    {
        $this->startedAt = $startedAt;
    }

    public function progressStep(): void
    {
        array_shift($this->steps);
    }

    /** @return Collection<int, MapAvailableActivity> */
    public function getMapAvailableActivities(): Collection
    {
        return $this->mapAvailableActivities;
    }

    public function setMapAvailableActivities(Collection $mapAvailableActivities): void
    {
        $this->mapAvailableActivities = $mapAvailableActivities;
    }

    public function addMapAvailableActivity(MapAvailableActivity $mapAvailableActivity): static
    {
        if (!$this->mapAvailableActivities->contains($mapAvailableActivity)) {
            $this->mapAvailableActivities->add($mapAvailableActivity);
            $mapAvailableActivity->setInvolvingActivity($this);
        }

        return $this;
    }
}