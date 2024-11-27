<?php

namespace App\Doctrine;

use Doctrine\ORM\Mapping\UnderscoreNamingStrategy;

class CustomNamingStrategy extends UnderscoreNamingStrategy
{
    public function classToTableName(string $className): string
    {
        $parts = explode('\\', $className);
        if ($parts[2] === 'Game') {
            return 'game_' . parent::classToTableName($className);
        }

        if ($parts[2] === 'Data') {
            return 'data_' . parent::classToTableName($className);
        }

        return parent::classToTableName($className);
    }
}