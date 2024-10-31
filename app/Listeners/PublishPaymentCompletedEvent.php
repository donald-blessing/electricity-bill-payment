<?php

namespace App\Listeners;

use App\Events\PaymentCompleted;
use App\Services\AWS\SNSService;
use App\Services\AWS\SQSService;
use Exception;

class PublishPaymentCompletedEvent
{
    private SNSService $snsService;
    private SQSService $sqsService;

    public function __construct(SNSService $snsService, SQSService $sqsService)
    {
        $this->snsService = $snsService;
        $this->sqsService = $sqsService;
    }

    /**
     * @throws Exception
     */
    public function handle(PaymentCompleted $event): void
    {
        // Publish to SNS
        $this->snsService->publishPaymentCompleted($event->toBroadcast());

        // Send to SQS for async processing
        $this->sqsService->sendPaymentNotification($event->toBroadcast());
    }
}
