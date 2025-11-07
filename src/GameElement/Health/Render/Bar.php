<?php

namespace App\GameElement\Health\Render;

use App\GameElement\Core\GameObject\GameObjectInterface;
use App\GameElement\Health\Component\HealthComponent;
use InvalidArgumentException;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;

#[AsTwigComponent(name: "Health:Bar")]
class Bar
{
    #[ExposeInTemplate]
    protected HealthComponent $health;

    #[ExposeInTemplate]
    protected GameObjectInterface $gameObject;

    public function mount(GameObjectInterface $gameObject): void
    {
        $health = $gameObject->getComponent(HealthComponent::getId());
        if (!$health) {
            throw new InvalidArgumentException('Health component not found for ' . $gameObject::class . '::' . $gameObject->getId());
        }
        $this->gameObject = $gameObject;
        $this->health = $health;
    }

    public function getHealth(): HealthComponent
    {
        return $this->health;
    }

    public function getGameObject(): GameObjectInterface
    {
        return $this->gameObject;
    }
}