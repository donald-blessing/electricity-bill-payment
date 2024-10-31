<?php

namespace App\Data;

use App\Models\Wallet;
use Spatie\LaravelData\Data;

class AddFundsToWalletData extends Data
{
    public function __construct(
        public float $amount,
        public Wallet $wallet
    ) {
    }
}
