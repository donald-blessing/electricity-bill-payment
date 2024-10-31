<?php

namespace App\Listeners;

use App\Events\LowBalanceDetected;
use App\Services\SmsService;

class LowAccountBalanceNotification
{
    private SmsService $smsService;

    public function __construct()
    {
        $this->smsService = new SmsService();
    }

    /**
     * Handle the event.
     */
    public function handle(LowBalanceDetected $event): void
    {
        $this->smsService->sendLowBalanceNotification($event->wallet);
    }
}
