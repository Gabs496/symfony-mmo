<?php

namespace App\GameElement\Core\GameObjectPrototype\Doctrine\Type;

use App\GameElement\Core\GameObjectPrototype\GameObjectPrototypeInterface;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;
use Symfony\Component\DependencyInjection\Attribute\When;

#[When(env: 'never')]
final class GameObjectPrototypeType extends StringType
{
    public function getName(): string
    {
        return  'game_object_prototype';
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        $column['length'] = 50;
        return parent::getSQLDeclaration($column, $platform);
    }

    /** @var GameObjectPrototypeInterface $value */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value->getId();
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?GameObjectPrototypeInterface
    {
        if ($value === null) {
            return null;
        }
        return new GameObjectPrototypePlaceholder($value);
    }
}