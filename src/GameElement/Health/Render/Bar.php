<?php

namespace App\GameElement\Health\Render;

use App\GameElement\Core\GameObject\GameObjectInterface;
use App\GameElement\Health\Component\Health;
use InvalidArgumentException;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;

#[AsTwigComponent(name: "Health:Bar")]
class Bar
{
    #[ExposeInTemplate]
    protected Health $health;

    #[ExposeInTemplate]
    protected GameObjectInterface $gameObject;

    public function mount(GameObjectInterface $gameObject): void
    {
        $health = $gameObject->getComponent(Health::class);
        if (!$health) {
            throw new InvalidArgumentException('Health component not found for ' . $gameObject::class . '::' . $gameObject->getId());
        }
        $this->gameObject = $gameObject;
        $this->health = $health;
    }

    public function getHealth(): Health
    {
        return $this->health;
    }

    public function getGameObject(): GameObjectInterface
    {
        return $this->gameObject;
    }
}