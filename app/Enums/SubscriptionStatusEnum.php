<?php

namespace App\Enums;

use App\Traits\EnumTrait;

enum SubscriptionStatusEnum: string
{
    use EnumTrait;

    case ACTIVE = 'active';
    case EXTENDED = 'extended';
    case CHANGED = 'changed';
}
