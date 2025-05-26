<?php

namespace App\GameElement\Mastery\Engine;

use App\GameElement\Mastery\MasteryType;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

readonly class MasteryTypeRepository
{
    public function __construct(
        #[AutowireIterator('mastery.type')]
        private iterable $masteryTypes,
    ) {
    }

    public function get(string $id): MasteryType
    {
        foreach ($this->masteryTypes as $masteryType) {
            if ($masteryType::getId() === $id) {
                return $masteryType;
            }
        }

        throw new \InvalidArgumentException(sprintf('Mastery type with id "%s" not found.', $id));
    }
}