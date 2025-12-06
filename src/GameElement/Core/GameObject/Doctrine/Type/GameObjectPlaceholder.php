<?php

namespace App\GameElement\Core\GameObject\Doctrine\Type;

use App\GameElement\Core\GameObject\GameObjectInterface;
use App\GameElement\Core\GameObject\GameObjectTrait;
use Symfony\Component\DependencyInjection\Attribute\When;

#[When(env: 'never')]
class GameObjectPlaceholder implements GameObjectInterface
{
    use GameObjectTrait {__construct as parentConstruct;}

    public function __construct(string $id)
    {
        $this->parentConstruct($id, []);
    }
}