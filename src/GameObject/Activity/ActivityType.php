<?php

namespace App\GameObject\Activity;

enum ActivityType: string
{
    case RESOURCE_GATHERING = 'RESOURCE_GATHERING';
    case RECIPE_CRAFTING = 'RECIPE_CRAFTING';
}
