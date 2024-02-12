<?php

namespace App\Enums;

use App\Traits\EnumTrait;

enum UserRolesEnum: string
{
    use EnumTrait;

    case ADMINISTRATOR = 'administrator';
    case BUSINESS = 'business';
    case EMPLOYEE = 'employee';
    case MEMBER = 'member';
}
