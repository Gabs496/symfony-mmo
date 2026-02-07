<?php

namespace App\GameElement\Core\GameComponent;

class AutoGameComponentSubscriber implements GameComponentSubscriberInterface
{
    private array $componentClasses = [];

    public function __construct()
    {
        $classes = get_declared_classes();
        foreach ($classes as $class) {
            if (is_subclass_of($class, GameComponent::class)) {
                $this->componentClasses[] = $class;
            }
        }
    }

    /** @return array<class-string<GameComponent>> */
    public function getSubscribedComponents(): array
    {
        return $this->componentClasses;
    }
}