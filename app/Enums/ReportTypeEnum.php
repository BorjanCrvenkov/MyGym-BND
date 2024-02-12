<?php

namespace App\Enums;

use App\Traits\EnumTrait;

enum ReportTypeEnum: string
{
    use EnumTrait;

    case USER = 'user';
    case GYM_PROBLEM = 'gym_problem';
}
