<?php

namespace App\Doctrine;

use Doctrine\ORM\Mapping\UnderscoreNamingStrategy;

class SubdirNamingStrategy extends UnderscoreNamingStrategy
{
    private const DIR_IGNORE = "App\\Entity\\";

    public function classToTableName(string $className): string
    {
        $subdirPath = str_replace(self::DIR_IGNORE, '', $className);
        $newClassname = self::DIR_IGNORE . str_replace('\\', '', $subdirPath);

        return parent::classToTableName($newClassname);
    }
}