<?php

namespace App\Twig\Components;

use App\GameElement\Character\Component\CharacterComponent;
use PennyPHP\Core\GameObject\GameObjectInterface;
use InvalidArgumentException;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;

#[AsTwigComponent(name: "HealthBar", template: 'components/HealthBar.html.twig')]
final class HealthBar
{
    #[ExposeInTemplate]
    protected CharacterComponent $characterComponent;

    #[ExposeInTemplate]
    protected GameObjectInterface $gameObject;

    public function mount(GameObjectInterface $gameObject): void
    {
        $characterComponent = $gameObject->getComponent(CharacterComponent::class);
        if (!$characterComponent) {
            throw new InvalidArgumentException('Character component not found for ' . $gameObject::class . '::' . $gameObject->getId());
        }
        $this->gameObject = $gameObject;
        $this->characterComponent = $characterComponent;
    }

    public function getCharacterComponent(): CharacterComponent
    {
        return $this->characterComponent;
    }

    public function getGameObject(): GameObjectInterface
    {
        return $this->gameObject;
    }
}