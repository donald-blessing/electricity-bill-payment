<?php

namespace App\Listeners;

use App\Events\BillCreated;
use App\Services\SmsService;

class SendBillCreatedNotification
{
    private SmsService $smsService;

    public function __construct()
    {
        $this->smsService = new SmsService();
    }

    /**
     * Handle the event.
     */
    public function handle(BillCreated $event): void
    {
        $this->smsService->sendBillCreatedNotification($event->bill);
    }
}
