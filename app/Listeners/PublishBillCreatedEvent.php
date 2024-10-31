<?php

namespace App\Listeners;

use App\Events\BillCreated;
use App\Services\AWS\SNSService;
use App\Services\AWS\SQSService;
use Exception;

class PublishBillCreatedEvent
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
    public function handle(BillCreated $event): void
    {
        // Publish to SNS
        $this->snsService->publishBillCreated($event->toBroadcast());

        // Send to SQS for async processing
        $this->sqsService->sendBillNotification($event->toBroadcast());
    }
}
