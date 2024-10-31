<?php

namespace App\Listeners;

use App\Events\PaymentCompleted;
use App\Services\SmsService;

class PaymentCompletedNotification
{
    private SmsService $smsService;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        $this->smsService = new SmsService();
    }

    /**
     * Handle the event.
     */
    public function handle(PaymentCompleted $event): void
    {
        $this->smsService->sendPaymentCompletedNotification($event->bill);
    }
}
