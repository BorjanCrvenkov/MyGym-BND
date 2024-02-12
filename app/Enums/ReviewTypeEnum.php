<?php

namespace App\Enums;

use App\Traits\EnumTrait;

enum ReviewTypeEnum: string
{
    use EnumTrait;

    case GYM_REVIEW = 'gym';
}
