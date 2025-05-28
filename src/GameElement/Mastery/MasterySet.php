<?php

namespace App\GameElement\Mastery;

class MasterySet
{
    /** @var Mastery[] */
    private array $masteries = [];

    public function increaseMasteryExperience(string $masteryType, float $experience): static
    {
        $mastery = $this->getMastery($masteryType);
        $mastery->increaseExperience($experience);
        return $this;
    }

    public function getMastery(string $masteryType): Mastery
    {
        //TODO: try to optimize
        foreach ($this->masteries as $mastery) {
            if ($mastery->getType() === $masteryType) {
                return $mastery;
            }
        }

        return $this->createMastery($masteryType);
    }

    private function createMastery(string $masteryType): Mastery
    {
        $mastery = new Mastery($masteryType, 0.0);
        $this->addMastery($mastery);
        return $mastery;
    }

    private function addMastery(Mastery $mastery): void
    {
        $this->masteries[] = $mastery;
    }

    /** @deprecated don't use */
    public function getMasteries(): array
    {
        return $this->masteries;
    }

    /** @deprecated don't use */
    public function setMasteries(array $masteries): void
    {
        $this->masteries = $masteries;
    }
}