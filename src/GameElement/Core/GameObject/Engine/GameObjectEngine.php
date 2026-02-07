<?php

namespace App\GameElement\Core\GameObject\Engine;

use App\GameElement\Core\GameObject\Entity\GameObject;
use App\GameElement\Core\GameObject\Exception\GameObjectNotFound;
use App\GameElement\Core\GameObject\Exception\RegisteredANonGameObjectException;
use App\GameElement\Core\GameObjectPrototype\AbstractGameObjectPrototype;
use App\GameElement\Core\GameObjectPrototype\GameObjectPrototypeInterface;
use App\Repository\Game\GameObjectRepository;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

readonly class GameObjectEngine
{
    public function __construct(
        #[AutowireIterator('game.object.prototype')]
        private iterable       $gameObjectPrototypeCollection,
        private GameObjectRepository $gameObjectRepository,
    )
    {
    }

    public function get(string $id): GameObject
    {
        return $this->gameObjectRepository->find($id);
    }

    public function getPrototype(string $type): GameObjectPrototypeInterface
    {
        foreach ($this->gameObjectPrototypeCollection as $gameObjectPrototype) {
            if (!$gameObjectPrototype instanceof GameObjectPrototypeInterface) {
                throw new RegisteredANonGameObjectException(sprintf('Class %s is tagged as game.object but does not extend %s',$gameObjectPrototype::class, GameObjectPrototypeInterface::class));
            }

            if ($type === $gameObjectPrototype->getType()) {
                return $gameObjectPrototype;
            }
        }
        throw new GameObjectNotFound('Game object prototype for class "'.$type.'" not found');
    }

    public function make(string $type): GameObject
    {
        if (is_subclass_of($type, AbstractGameObjectPrototype::class)) {
            $type = $type::getType();
        }

        $prototype = $this->getPrototype($type);
        return $prototype->make();
    }
}