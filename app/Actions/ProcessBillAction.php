<?php

namespace App\Actions;

use App\Data\ProcessBillPaymentData;
use App\Enums\BillStatus;
use App\Events\LowBalanceDetected;
use App\Events\PaymentCompleted;
use App\Jobs\ProcessSNSNotification;
use App\Services\Providers\ElectricityProvider;
use Exception;

class ProcessBillAction
{
    /**
     * @throws Exception
     */
    public static function execute(ProcessBillPaymentData $data): array
    {
        $bill = $data->bill;

        if ($bill->status === BillStatus::PAID) {
            return ['message' => 'Bill has already been paid'];
        }

        $wallet = $bill->user->wallet;

        if ($wallet->balance < $bill->amount) {
            event(new LowBalanceDetected($wallet));
            ProcessSNSNotification::dispatch($wallet->toArray(), 'low_balance');
            return ['message' => 'Insufficient wallet funds'];
        }

        $provider = ElectricityProvider::create($bill->provider);
        $token    = $provider->processPayment($bill->amount);

        //update wallet and bill
        $wallet->balance -= $bill->amount;
        $wallet->save();

        $bill->status = BillStatus::PAID;
        $bill->token  = $token;
        $bill->save();

        // Fire PaymentCompleted event
        event(new PaymentCompleted($bill));
        ProcessSNSNotification::dispatch($bill->toArray(), 'payment_completed');

        return [
            'token' => $token,
        ];
    }


}
