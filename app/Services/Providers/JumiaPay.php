<?php

namespace App\Services\Providers;

use App\Services\Traits\GenerateTokenTrait;

class JumiaPay
{
    use GenerateTokenTrait;

    public function processPayment(float $amount): string
    {
        return $this->generateToken();
    }
}
