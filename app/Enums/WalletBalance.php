<?php

namespace App\Enums;

use App\Enums\Traits\EnumsTrait;

enum WalletBalance: int
{
    use EnumsTrait;

    case LOW_BALANCE_THRESHOLD = 1000;
}
