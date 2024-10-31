<?php

namespace App\Actions;

use App\Data\AddFundsToWalletData;
use App\Enums\WalletBalance;
use App\Events\LowBalanceDetected;
use App\Jobs\ProcessSNSNotification;
use App\Models\Wallet;

class DeductFundsFromWalletAction
{
    public static function execute(AddFundsToWalletData $data): Wallet
    {
        $wallet          = $data->wallet;
        $wallet->balance -= $data->amount;
        $wallet->save();

        if ($wallet->balance < WalletBalance::LOW_BALANCE_THRESHOLD) {
            event(new LowBalanceDetected($wallet));
            ProcessSNSNotification::dispatch($wallet->toArray(), 'low_balance');
        }

        return $wallet;
    }
}
