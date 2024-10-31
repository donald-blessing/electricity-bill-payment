<?php

namespace App\Actions;

use App\Data\AddFundsToWalletData;
use App\Events\FundsAdded;
use App\Models\Wallet;

class AddFundsToWalletAction
{
    public static function execute(AddFundsToWalletData $data): Wallet
    {
        $wallet = $data->wallet;

        $wallet->update([
            'balance' => $wallet->balance + $data->amount,
        ]);

        event(new FundsAdded($wallet));

        return $wallet->refresh();
    }
}
