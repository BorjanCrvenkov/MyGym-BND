<?php

namespace App\Enums;

use App\Traits\EnumTrait;

enum FileTypeEnum: string
{
    use EnumTrait;

    case USER_IMAGE = 'user_image';
    case USER_IDENTIFICATION_DOCUMENT = 'user_identification';
    case GYM_COVER_IMAGE = 'gym_cover_image';
    case GYM_IMAGE = 'gym_image';
    case EQUIPMENT_IMAGE = 'equipment_image';
}
