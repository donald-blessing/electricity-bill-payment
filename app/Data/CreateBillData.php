<?php

namespace App\Data;

use App\Enums\BillStatus;
use Spatie\LaravelData\Data;

class CreateBillData extends Data
{
    public string $status;

    public function __construct(
        public float $amount,
        public string $provider,
        public int $user_id,
    ) {
        $this->status = BillStatus::PENDING->value;
    }
}
