<?php

namespace App\Data;

use App\Models\Bill;
use Spatie\LaravelData\Data;

class ProcessBillPaymentData extends Data
{
    public Bill $bill;

    public function __construct(
        public string $validationRef

    ) {
        $this->bill = Bill::query()->find($validationRef)->first();
    }
}
