<?php

namespace App\GameObject\Map;

use App\GameElement\Map\AbstractMap;
use App\GameElement\MapResource\MapWithSpawningResourceInterface;

abstract readonly class AbstractBaseMap extends AbstractMap implements MapWithSpawningResourceInterface
{

}