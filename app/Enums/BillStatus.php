<?php

namespace App\Enums;

use App\Enums\Traits\EnumsTrait;

enum BillStatus: string
{
    use EnumsTrait;

    case PENDING = 'pending';
    case PAID = 'paid';
}
