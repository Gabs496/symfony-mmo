<?php

namespace App\GameElement\Core\GameObject\Doctrine\Type;

use App\GameElement\Core\GameObject\GameObjectInterface;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;
use Symfony\Component\DependencyInjection\Attribute\When;

#[When(env: 'never')]
final class GameObjectType extends StringType
{
    public function getName(): string
    {
        return  'game_object';
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        $column['length'] = 50;
        return parent::getSQLDeclaration($column, $platform);
    }

    /** @var GameObjectInterface $value */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value->getId();
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?GameObjectInterface
    {
        if ($value === null) {
            return null;
        }
        return new GameObjectPlaceholder($value);
    }
}