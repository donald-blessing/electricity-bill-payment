<?php

namespace App\Services\Providers;

use App\Enums\BillProvider;
use Exception;

class ElectricityProvider
{
    /**
     * @throws Exception
     */
    public static function create($provider): Remita|JumiaPay|BuyPower|IRecharge
    {
        $provider = BillProvider::from($provider);
        
        return match ($provider) {
            BillProvider::Remita    => new Remita(),
            BillProvider::BuyPower  => new BuyPower(),
            BillProvider::IRecharge => new IRecharge(),
            BillProvider::JumiaPay  => new JumiaPay(),
            default                 => throw new Exception('Invalid provider'),
        };
    }
}
