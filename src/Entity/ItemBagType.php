<?php

namespace App\Entity;

enum ItemBagType: string
{
    case BACKPACK = 'BACKPACK';
    case EQUIPMENT = 'EQUIPMENT';
    case RESOURCES = 'RESOURCES';

}
