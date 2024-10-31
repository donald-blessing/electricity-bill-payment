<?php

namespace App\Enums;

use App\Enums\Traits\EnumsTrait;

enum BillProvider: string
{
    use EnumsTrait;

    case BuyPower = 'BuyPower';
    case IRecharge = 'iRecharge';
    case Remita = 'Remita';
    case JumiaPay = 'JumiaPay';
}
