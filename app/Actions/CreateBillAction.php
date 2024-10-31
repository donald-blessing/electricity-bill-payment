<?php

namespace App\Actions;

use App\Data\CreateBillData;
use App\Events\BillCreated;
use App\Jobs\ProcessSNSNotification;
use App\Models\Bill;

class CreateBillAction
{
    public static function execute(CreateBillData $data)
    {
        $bill = Bill::query()->create(
            $data->except('user')->toArray()
        );

        event(new BillCreated($bill));

        ProcessSNSNotification::dispatch($bill->toArray(), 'bill_created');

        return $bill;
    }
}
