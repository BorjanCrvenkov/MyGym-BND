<?php

namespace App\Enums;

use App\Traits\EnumTrait;

enum ExpenseStatusEnum: string
{
    use EnumTrait;

    case PAID = 'paid';
    case NOT_PAID = 'not paid';
}
