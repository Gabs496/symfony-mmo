<?php

namespace App\GameElement\Core\GameObjectPrototype\Doctrine\Type;

use App\GameElement\Core\GameObjectPrototype\GameObjectPrototypeInterface;
use Symfony\Component\DependencyInjection\Attribute\When;

#[When(env: 'never')]
readonly class GameObjectPrototypePlaceholder implements GameObjectPrototypeInterface
{
    public function __construct(
        private string $prototypeId,
    )
    {
    }

    public function make(array $components = [])
    {
        throw new \LogicException('This is a placeholder class and should not be used directly.');
    }

    public static function getId(): string
    {
        throw new \LogicException('This is a placeholder class and should not be used directly.');
    }

    public function getPrototypeId(): string
    {
        return $this->prototypeId;
    }
}