<?php

namespace App\Actions;

use App\Data\CreateBillData;
use App\Events\BillCreated;
use App\Events\LowBalanceDetected;
use App\Jobs\ProcessSNSNotification;
use App\Models\Bill;
use App\Models\User;

class CreateBillAction
{
    public static function execute(CreateBillData $data)
    {
        $user   = User::query()->find($data->user_id);
        $wallet = $user->wallet;

        if ($wallet->balance < $data->amount) {
            event(new LowBalanceDetected($wallet));
            ProcessSNSNotification::dispatch($wallet->toArray(), 'low_balance');
            return ['message' => 'Insufficient wallet funds'];
        }

        $bill = Bill::query()->create(
            $data->except('user')->toArray()
        );

        event(new BillCreated($bill));

        ProcessSNSNotification::dispatch($bill->toArray(), 'bill_created');

        return $bill;
    }
}
